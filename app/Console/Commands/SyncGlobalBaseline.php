<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\WeatherForecast;
use App\Services\ExternalIntelligenceService;
use App\Services\RiskScoringService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncGlobalBaseline extends Command
{
    protected $signature = 'countries:sync-global';
    protected $description = 'Isi metadata, tren ekonomi, cuaca, dan risiko seluruh negara secara batch';

    public function handle(RiskScoringService $riskScoring): int
    {
        $metadataResponse = Http::timeout(45)->retry(3, 500)->get('https://api.worldbank.org/v2/country', [
            'format' => 'json', 'per_page' => 400,
        ]);
        $metadata = collect($metadataResponse->json('1', []))->keyBy('id');
        $iso3ToCountry = [];

        foreach ($metadata as $iso3 => $row) {
            $iso2 = $row['iso2Code'] ?? null;
            if (! $iso2 || strlen($iso2) !== 2) continue;
            $country = Country::where('country_code', $iso2)->first();
            if (! $country) continue;
            $country->update([
                'capital' => $row['capitalCity'] ?: $country->capital,
                'region' => $row['region']['value'] ?: $country->region,
                'latitude' => is_numeric($row['latitude'] ?? null) ? $row['latitude'] : $country->latitude,
                'longitude' => is_numeric($row['longitude'] ?? null) ? $row['longitude'] : $country->longitude,
            ]);
            $iso3ToCountry[$iso3] = $country->id;
        }

        foreach (ExternalIntelligenceService::INDICATORS as $code => $name) {
            $response = Http::timeout(60)->retry(3, 500)->get("https://api.worldbank.org/v2/country/all/indicator/{$code}", [
                'format' => 'json', 'date' => (now()->year - 10).':'.now()->year, 'per_page' => 25000,
            ]);
            $rows = [];
            foreach ($response->json('1', []) as $row) {
                $countryId = $iso3ToCountry[$row['countryiso3code'] ?? ''] ?? null;
                if (! $countryId || $row['value'] === null) continue;
                $rows[] = [
                    'country_id' => $countryId, 'indicator_code' => $code, 'indicator_name' => $name,
                    'indicator_value' => $row['value'], 'recorded_year' => (int) $row['date'],
                    'created_at' => now(), 'updated_at' => now(),
                ];
            }
            foreach (array_chunk($rows, 1000) as $chunk) {
                EconomicIndicator::upsert($chunk, ['country_id', 'indicator_code', 'recorded_year'], ['indicator_name', 'indicator_value', 'updated_at']);
            }
            $this->line("✓ {$name}: ".count($rows).' data');
        }

        Country::whereNotNull('latitude')->whereNotNull('longitude')->orderBy('id')->chunk(40, function ($countries) {
            $response = rescue(fn () => Http::timeout(45)->retry(2, 500)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $countries->pluck('latitude')->implode(','),
                'longitude' => $countries->pluck('longitude')->implode(','),
                'current' => 'temperature_2m,precipitation,weather_code,wind_speed_10m',
            ]), null, false);
            if (! $response?->successful()) return;
            $payload = $response->json();
            if (isset($payload['latitude'])) $payload = [$payload];
            foreach ($countries->values() as $index => $country) {
                $current = $payload[$index]['current'] ?? null;
                if (! $current) continue;
                WeatherForecast::create([
                    'country_id' => $country->id, 'temperature' => $current['temperature_2m'] ?? null,
                    'precipitation' => $current['precipitation'] ?? null, 'wind_speed' => $current['wind_speed_10m'] ?? null,
                    'weather_code' => $current['weather_code'] ?? null, 'condition_status' => $this->weatherLabel($current['weather_code'] ?? null),
                    'recorded_at' => $current['time'] ?? now(),
                ]);
            }
        });

        Country::with(['weatherForecasts', 'economicIndicators', 'currencyRates', 'newsCache'])->each(fn ($country) => $riskScoring->calculate($country));
        $this->info('Sinkronisasi global selesai untuk '.Country::count().' negara.');
        return self::SUCCESS;
    }

    private function weatherLabel(?int $code): string
    {
        return match (true) {
            $code === 0 => 'Cerah', $code <= 3 => 'Berawan', $code <= 48 => 'Berkabut',
            $code <= 67 => 'Hujan', $code <= 77 => 'Salju', $code <= 82 => 'Hujan Lebat',
            $code >= 95 => 'Badai Petir', default => 'Tidak diketahui',
        };
    }
}
