<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCountryProfiles extends Command
{
    protected $signature = 'countries:sync-profiles';

    protected $description = 'Sinkronkan profil seluruh negara dari REST Countries';

    public function handle(): int
    {
        $key = config('services.rest_countries.key');
        if (! $key) {
            $response = Http::timeout(60)->retry(3, 500)->get('https://countries.dev/countries');
            if (! $response->successful()) {
                $this->error('countries.dev tidak tersedia (HTTP '.$response->status().').');

                return self::FAILURE;
            }
            $count = 0;
            foreach ($response->json() as $row) {
                $code = $row['alpha2Code'] ?? null;
                if (! $code || ! Country::where('country_code', $code)->exists()) {
                    continue;
                }
                Country::where('country_code', $code)->update([
                    'country_name' => $row['name'] ?? $code,
                    'capital' => $row['capital'] ?? null,
                    'currency' => $row['currencies'][0]['code'] ?? null,
                    'region' => $row['region'] ?? null,
                    'population' => $row['population'] ?? null,
                    'latitude' => $row['latlng'][0] ?? null,
                    'longitude' => $row['latlng'][1] ?? null,
                    'languages' => json_encode(collect($row['languages'] ?? [])->pluck('name')->values()->all()),
                    'data_source' => 'countries.dev',
                    'is_estimated' => false,
                ]);
                $count++;
            }
            $this->info("{$count} profil negara disinkronkan dari countries.dev.");

            return self::SUCCESS;
        }
        $count = 0;
        for ($offset = 0; ; $offset += 100) {
            $response = Http::withToken($key)->timeout(60)->retry(3, 500)->get('https://api.restcountries.com/countries/v5', [
                'response_fields' => 'names.common,codes.alpha_2,capitals,currencies,region,population,coordinates',
                'limit' => 100, 'offset' => $offset,
            ]);
            if (! $response->successful()) {
                $this->error('REST Countries tidak tersedia (HTTP '.$response->status().').');

                return self::FAILURE;
            }
            $objects = $response->json('data.objects', []);
            foreach ($objects as $row) {
                $code = $row['codes']['alpha_2'] ?? null;
                if (! $code) {
                    continue;
                }
                Country::updateOrCreate(['country_code' => $code], [
                    'country_name' => $row['names']['common'] ?? $code,
                    'capital' => $row['capitals'][0]['name'] ?? null,
                    'currency' => $row['currencies'][0]['code'] ?? null,
                    'region' => $row['region'] ?? null,
                    'population' => $row['population'] ?? null,
                    'latitude' => $row['coordinates']['lat'] ?? null,
                    'longitude' => $row['coordinates']['lng'] ?? null,
                    'languages' => collect($row['languages'] ?? [])->pluck('name')->values()->all(),
                    'data_source' => 'rest-countries-v5',
                    'is_estimated' => false,
                ]);
                $count++;
            }
            if (count($objects) < 100) {
                break;
            }
        }
        $this->info("{$count} profil negara disinkronkan.");

        return self::SUCCESS;
    }
}
