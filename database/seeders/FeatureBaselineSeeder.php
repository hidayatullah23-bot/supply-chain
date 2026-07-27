<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CurrencyExchangeRate;
use App\Models\EconomicIndicator;
use App\Models\NewsCache;
use App\Models\RiskScore;
use App\Models\RiskScoreHistory;
use App\Models\WeatherForecast;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureBaselineSeeder extends Seeder
{
    public function run(): void
    {
        Country::query()->orderBy('id')->each(function (Country $country): void {
            $seed = abs(crc32($country->country_code));

            $population = $country->economicIndicators()
                ->where('indicator_code', 'SP.POP.TOTL')
                ->latest('recorded_year')
                ->value('indicator_value');

            $country->update([
                'capital' => $country->capital ?: 'Data belum tersedia',
                'currency' => $country->currency ?: 'USD',
                'region' => $country->region ?: 'Other',
                'population' => $country->population ?: ($population ? (int) $population : 100_000 + ($seed % 80_000_000)),
            ]);

            if (! $country->weatherForecasts()->exists()) {
                WeatherForecast::create([
                    'country_id' => $country->id,
                    'temperature' => 15 + ($seed % 16),
                    'precipitation' => ($seed % 50) / 10,
                    'wind_speed' => 5 + ($seed % 30),
                    'weather_code' => 3,
                    'condition_status' => 'Baseline estimasi',
                    'recorded_at' => now(),
                    'data_source' => 'internal-baseline',
                    'is_estimated' => true,
                ]);
            }

            foreach ([
                'NY.GDP.MKTP.CD' => ['GDP', 1_000_000_000 + ($seed % 900_000_000_000)],
                'FP.CPI.TOTL.ZG' => ['Inflation', 1 + (($seed % 120) / 10)],
                'SP.POP.TOTL' => ['Population', $country->population ?: 100_000 + ($seed % 80_000_000)],
                'NE.EXP.GNFS.CD' => ['Exports', 100_000_000 + ($seed % 90_000_000_000)],
                'NE.IMP.GNFS.CD' => ['Imports', 120_000_000 + ($seed % 95_000_000_000)],
            ] as $code => [$name, $value]) {
                if (! $country->economicIndicators()->where('indicator_code', $code)->exists()) {
                    EconomicIndicator::updateOrCreate(
                        ['country_id' => $country->id, 'indicator_code' => $code, 'recorded_year' => now()->year - 1],
                        ['indicator_name' => $name.' (baseline)', 'indicator_value' => $value, 'data_source' => 'internal-baseline', 'is_estimated' => true]
                    );
                }
            }

            CurrencyExchangeRate::firstOrCreate(
                [
                    'country_id' => $country->id,
                    'currency_code' => strtoupper($country->currency ?: 'USD'),
                    'recorded_date' => now()->toDateString(),
                ],
                ['exchange_rate' => 1, 'data_source' => 'internal-baseline', 'is_estimated' => true]
            );

            if (! $country->newsCache()->exists()) {
                NewsCache::create([
                    'country_id' => $country->id,
                    'title' => "Baseline intelijen rantai pasok {$country->country_name}",
                    'description' => "Belum ada berita eksternal terbaru yang berhasil diambil untuk {$country->country_name}. Entri netral ini menandai bahwa pemantauan aktif dan akan diganti saat provider berita tersedia.",
                    'source_url' => 'https://gnews.io/',
                    'sentiment_status' => 'Neutral',
                    'sentiment_score_positive' => 0,
                    'sentiment_score_negative' => 0,
                    'data_source' => 'internal-baseline',
                    'is_estimated' => true,
                ]);
            }

            if (! $country->riskScore()->exists()) {
                $weather = 15 + ($seed % 66);
                $inflation = 10 + (($seed >> 3) % 71);
                $news = 50;
                $currency = 10 + (($seed >> 6) % 66);
                $total = round($weather * .30 + $inflation * .20 + $news * .40 + $currency * .10, 2);
                $level = $total >= 70 ? 'High Risk' : ($total >= 40 ? 'Medium Risk' : 'Low Risk');
                RiskScore::create([
                    'country_id' => $country->id,
                    'weather_risk' => $weather,
                    'inflation_risk' => $inflation,
                    'news_sentiment_risk' => $news,
                    'currency_risk' => $currency,
                    'total_risk_score' => $total,
                    'risk_level' => $level,
                ]);
            }

            if (! $country->riskHistory()->exists()) {
                $score = $country->riskScore;
                RiskScoreHistory::create([
                    'country_id' => $country->id,
                    'weather_risk' => $score->weather_risk,
                    'inflation_risk' => $score->inflation_risk,
                    'news_sentiment_risk' => $score->news_sentiment_risk,
                    'currency_risk' => $score->currency_risk,
                    'total_risk_score' => $score->total_risk_score,
                    'risk_level' => $score->risk_level,
                ]);
            }
        });

        Country::with('riskScore')->get()->sortByDesc(fn (Country $country) => $country->riskScore?->total_risk_score ?? 0)
            ->take(24)->each(function (Country $country): void {
                $severity = ($country->riskScore?->total_risk_score ?? 0) >= 70 ? 'High' : 'Medium';
                DB::table('disruptions')->updateOrInsert(
                    ['title' => "Pemantauan operasional {$country->country_name}"],
                    [
                        'description' => 'Sinyal baseline berdasarkan kombinasi risiko cuaca, inflasi, berita, dan volatilitas kurs. Verifikasi dengan sumber operasional sebelum mengambil keputusan.',
                        'severity_level' => $severity,
                        'affected_country_id' => $country->id,
                        'data_source' => 'weighted-risk-baseline',
                        'is_estimated' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            });

        if (DB::table('news_sentiments')->count() === 0) {
            DB::table('news_cache')->orderBy('id')->eachById(function ($news): void {
                DB::table('news_sentiments')->insert([
                    'country_id' => $news->country_id,
                    'title' => $news->title,
                    'description' => $news->description ?: 'Tidak ada deskripsi.',
                    'source_url' => $news->source_url,
                    'sentiment_status' => $news->sentiment_status,
                    'sentiment_score_positive' => $news->sentiment_score_positive,
                    'sentiment_score_negative' => $news->sentiment_score_negative,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        }
    }
}
