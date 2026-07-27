<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\CurrencyExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncCurrencyRates extends Command
{
    protected $signature = 'countries:sync-currencies';

    protected $description = 'Sinkronkan kurs aktual seluruh mata uang terhadap USD';

    public function handle(): int
    {
        $response = Http::timeout(45)->retry(3, 500)->get('https://open.er-api.com/v6/latest/USD');
        if (! $response->successful() || $response->json('result') !== 'success') {
            $this->error('ExchangeRate-API tidak tersedia.');

            return self::FAILURE;
        }

        $rates = $response->json('rates', []);
        $date = now()->parse($response->json('time_last_update_utc', now()))->toDateString();
        $updated = 0;

        Country::whereNotNull('currency')->each(function (Country $country) use ($rates, $date, &$updated): void {
            $currency = strtoupper($country->currency);
            if (! isset($rates[$currency])) {
                return;
            }
            CurrencyExchangeRate::updateOrCreate(
                ['country_id' => $country->id, 'currency_code' => $currency, 'recorded_date' => $date],
                ['exchange_rate' => $rates[$currency], 'data_source' => 'exchange-rate-api', 'is_estimated' => false]
            );
            $updated++;
        });

        $historyResponse = Http::timeout(45)->retry(2, 500)->get(
            'https://api.frankfurter.app/'.now()->subDays(30)->toDateString().'..'.now()->toDateString(),
            ['from' => 'USD']
        );
        if ($historyResponse->successful()) {
            $countriesByCurrency = Country::whereNotNull('currency')->get()->groupBy(fn (Country $country) => strtoupper($country->currency));
            foreach ($historyResponse->json('rates', []) as $rateDate => $dailyRates) {
                foreach ($dailyRates as $currency => $rate) {
                    foreach ($countriesByCurrency->get($currency, collect()) as $country) {
                        CurrencyExchangeRate::updateOrCreate(
                            ['country_id' => $country->id, 'currency_code' => $currency, 'recorded_date' => $rateDate],
                            ['exchange_rate' => $rate, 'data_source' => 'frankfurter', 'is_estimated' => false]
                        );
                    }
                }
            }
        }

        $this->info("{$updated} kurs negara diperbarui dari ExchangeRate-API.");

        return self::SUCCESS;
    }
}
