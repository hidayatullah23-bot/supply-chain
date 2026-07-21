<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WatchlistSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@supplychain.test'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $priorityData = [
            'ID' => [42, 38, 45, 50],
            'CN' => [55, 42, 68, 48],
            'DE' => [18, 20, 22, 25],
            'AU' => [24, 18, 16, 20],
            'SG' => [15, 12, 18, 16],
            'JP' => [36, 22, 28, 30],
            'US' => [30, 35, 42, 25],
            'RU' => [70, 75, 90, 65],
        ];

        Country::orderBy('country_name')->each(function (Country $country) use ($user, $priorityData) {
            if (isset($priorityData[$country->country_code])) {
                [$weather, $inflation, $news, $currency] = $priorityData[$country->country_code];
            } else {
                // Skor demo deterministik agar stabil pada setiap proses seeding.
                $seed = abs(crc32($country->country_code));
                $weather = 12 + ($seed % 69);
                $inflation = 8 + (($seed >> 3) % 73);
                $news = 10 + (($seed >> 6) % 76);
                $currency = 8 + (($seed >> 9) % 68);
            }

            $total = round(($weather * .30) + ($inflation * .20) + ($news * .40) + ($currency * .10), 2);
            $level = $total >= 70 ? 'High Risk' : ($total >= 40 ? 'Medium Risk' : 'Low Risk');

            RiskScore::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'weather_risk' => $weather,
                    'inflation_risk' => $inflation,
                    'news_sentiment_risk' => $news,
                    'currency_risk' => $currency,
                    'total_risk_score' => $total,
                    'risk_level' => $level,
                ]
            );

            Watchlist::firstOrCreate([
                'user_id' => $user->id,
                'country_id' => $country->id,
            ]);
        });
    }
}
