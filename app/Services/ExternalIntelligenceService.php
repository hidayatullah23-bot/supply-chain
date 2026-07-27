<?php

namespace App\Services;

use App\Models\Country;
use App\Models\CurrencyExchangeRate;
use App\Models\EconomicIndicator;
use App\Models\WeatherForecast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExternalIntelligenceService
{
    public const INDICATORS = [
        'NY.GDP.MKTP.CD' => 'GDP',
        'FP.CPI.TOTL.ZG' => 'Inflation',
        'SP.POP.TOTL' => 'Population',
        'NE.EXP.GNFS.CD' => 'Exports',
        'NE.IMP.GNFS.CD' => 'Imports',
    ];

    public function syncCountryProfile(Country $country): Country
    {
        $data = Cache::remember("rest-country:{$country->country_code}", now()->addDay(), function () use ($country) {
            $key = config('services.rest_countries.key');
            if ($key) {
                $response = rescue(fn () => Http::withToken($key)->timeout(12)->retry(2, 300)->get(
                    'https://api.restcountries.com/countries/v5/codes.alpha_2/'.$country->country_code,
                    ['response_fields' => 'names.common,capitals,currencies,region,population,coordinates,languages']
                ), null, false);
                $row = $response?->successful() ? $response->json('data.objects.0') : null;
                if ($row) {
                    return [
                        'name' => $row['names']['common'] ?? null,
                        'capital' => $row['capitals'][0]['name'] ?? null,
                        'currency' => $row['currencies'][0]['code'] ?? null,
                        'region' => $row['region'] ?? null,
                        'population' => $row['population'] ?? null,
                        'latitude' => $row['coordinates']['lat'] ?? null,
                        'longitude' => $row['coordinates']['lng'] ?? null,
                        'languages' => collect($row['languages'] ?? [])->pluck('name')->values()->all(),
                        'source' => 'rest-countries-v5',
                    ];
                }
            }

            $response = rescue(fn () => Http::timeout(12)->retry(2, 300)->get('https://countries.dev/alpha/'.$country->country_code), null, false);
            $row = $response?->successful() ? $response->json() : null;

            return $row ? [
                'name' => $row['name'] ?? null,
                'capital' => $row['capital'] ?? null,
                'currency' => $row['currencies'][0]['code'] ?? null,
                'region' => $row['region'] ?? null,
                'population' => $row['population'] ?? null,
                'latitude' => $row['latlng'][0] ?? null,
                'longitude' => $row['latlng'][1] ?? null,
                'languages' => collect($row['languages'] ?? [])->pluck('name')->values()->all(),
                'source' => 'countries.dev',
            ] : null;
        });
        if ($data) {
            $country->update([
                'country_name' => $data['name'] ?? $country->country_name,
                'capital' => $data['capital'] ?? $country->capital,
                'currency' => $data['currency'] ?? $country->currency,
                'region' => $data['region'] ?? $country->region,
                'population' => $data['population'] ?? $country->population,
                'latitude' => $data['latitude'] ?? $country->latitude,
                'longitude' => $data['longitude'] ?? $country->longitude,
                'languages' => $data['languages'] ?: $country->languages,
                'data_source' => $data['source'],
                'is_estimated' => false,
            ]);
        }

        return $country->refresh();
    }

    public function syncEconomics(Country $country): array
    {
        $result = [];
        foreach (self::INDICATORS as $code => $name) {
            $rows = Cache::remember("wb:{$country->country_code}:{$code}", now()->addHours(12), function () use ($country, $code) {
                $response = rescue(fn () => Http::timeout(12)->retry(2, 250)->get("https://api.worldbank.org/v2/country/{$country->country_code}/indicator/{$code}", ['format' => 'json', 'date' => (now()->year - 10).':'.now()->year, 'per_page' => 20]), null, false);

                return $response?->successful() ? ($response->json('1') ?? []) : [];
            });
            foreach ($rows as $row) {
                if ($row['value'] === null) {
                    continue;
                }
                EconomicIndicator::updateOrCreate(
                    ['country_id' => $country->id, 'indicator_code' => $code, 'recorded_year' => (int) $row['date']],
                    ['indicator_name' => $name, 'indicator_value' => $row['value'], 'data_source' => 'world-bank', 'is_estimated' => false]
                );
            }
            $result[$code] = EconomicIndicator::where('country_id', $country->id)->where('indicator_code', $code)->orderBy('recorded_year')->get();
        }

        return $result;
    }

    public function syncWeather(Country $country): ?WeatherForecast
    {
        if ($country->latitude === null || $country->longitude === null) {
            return null;
        }
        $response = rescue(fn () => Http::timeout(10)->retry(2, 200)->get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $country->latitude, 'longitude' => $country->longitude,
            'current' => 'temperature_2m,precipitation,weather_code,wind_speed_10m',
        ]), null, false);
        if (! $response?->successful()) {
            return $country->weatherForecasts()->latest('recorded_at')->first();
        }
        $current = $response->json('current');

        return WeatherForecast::create([
            'country_id' => $country->id, 'temperature' => $current['temperature_2m'] ?? null,
            'precipitation' => $current['precipitation'] ?? null, 'wind_speed' => $current['wind_speed_10m'] ?? null,
            'weather_code' => $current['weather_code'] ?? null, 'condition_status' => $this->weatherLabel($current['weather_code'] ?? null),
            'recorded_at' => $current['time'] ?? now(),
            'data_source' => 'open-meteo', 'is_estimated' => false,
        ]);
    }

    public function syncCurrency(Country $country): array
    {
        $currency = strtoupper($country->currency ?: 'USD');
        if ($currency === 'USD') {
            return [];
        }
        $start = now()->subDays(30)->toDateString();
        $end = now()->toDateString();
        $response = rescue(fn () => Http::timeout(10)->retry(2, 200)->get("https://api.frankfurter.app/{$start}..{$end}", ['from' => 'USD', 'to' => $currency]), null, false);
        if ($response?->successful()) {
            foreach ($response->json('rates', []) as $date => $rates) {
                if (! isset($rates[$currency])) {
                    continue;
                }
                CurrencyExchangeRate::updateOrCreate(
                    ['country_id' => $country->id, 'currency_code' => $currency, 'recorded_date' => $date],
                    ['exchange_rate' => $rates[$currency], 'data_source' => 'frankfurter', 'is_estimated' => false]
                );
            }
        }

        return CurrencyExchangeRate::where('country_id', $country->id)->where('currency_code', $currency)->orderBy('recorded_date')->get()->all();
    }

    private function weatherLabel(?int $code): string
    {
        return match (true) {
            $code === 0 => 'Cerah', $code <= 3 => 'Berawan', $code <= 48 => 'Berkabut', $code <= 67 => 'Hujan', $code <= 77 => 'Salju', $code <= 82 => 'Hujan Lebat', $code >= 95 => 'Badai Petir', default => 'Tidak diketahui'
        };
    }
}
