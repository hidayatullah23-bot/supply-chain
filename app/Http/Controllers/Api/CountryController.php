<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\RiskScore;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::with(['riskScore'])->get();
        return view('countries.index', compact('countries'));
    }

    public function news($id)
    {
        return app(NewsController::class)->showNewsPage($id);
    }
    
    public function dashboard($id)
    {
        // Mengambil data negara beserta relasi riskScore saja
        $countryData = Country::with(['riskScore'])->findOrFail($id);
        
        return view('countries.dashboard', compact('countryData'));
    }

    public function compare(Request $request)
    {
        $countries = DB::table('countries')->get();
        
        $country1 = null;
        $country2 = null;
        $risk1 = null;
        $risk2 = null;

        if ($request->has('country_id_1') && $request->has('country_id_2')) {
            $country1 = Country::with(['riskScore'])->find($request->country_id_1);
            $country2 = Country::with(['riskScore'])->find($request->country_id_2);
        }

        return view('countries.compare', compact('countries', 'country1', 'country2'));
    }

    public function mapView()
    {
        $countries = Country::with(['riskScore'])->get();
        return view('countries.map', compact('countries'));
    }

    public function currencyChart($id)
    {
        $countryData = Country::findOrFail($id);
        
        // Data historis tren kurs untuk demonstrasi grafik Chart.js
        $currencyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
        $currencyValues = [15000, 15100, 14900, 15200, 15350, 15250, 15400];

        return view('countries.currency', compact('countryData', 'currencyLabels', 'currencyValues'));
    }

    public function edit($id)
    {
        $countryData = DB::table('countries')->where('id', $id)->first();

        if (!$countryData) {
            abort(404, 'Data negara tidak ditemukan.');
        }

        return view('countries.edit', compact('countryData'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'country_name' => 'required|string|max:255',
        ]);

        DB::table('countries')->where('id', $id)->update([
            'country_name' => $request->country_name,
            'updated_at' => now(),
        ]);

        return redirect('/countries')->with('success', 'Data negara berhasil diperbarui!');
    }

    public function calculateRisk($countryId)
    {
        $country = Country::findOrFail($countryId);

        // 1. Ambil data cuaca real-time dari Open-Meteo API
        $weatherScore = 20.00;
        if ($country->latitude && $country->longitude) {
            $response = Http::get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $country->latitude,
                'longitude' => $country->longitude,
                'current_weather' => true,
            ]);

            if ($response->successful()) {
                $weatherData = $response->json()['current_weather'] ?? null;
                if ($weatherData) {
                    $temperature = $weatherData['temperature'];
                    $windspeed = $weatherData['windspeed'];

                    $weatherScore = 10.00;
                    if ($windspeed > 20) {
                        $weatherScore += 40.00;
                    }
                    if ($temperature < 0 || $temperature > 35) {
                        $weatherScore += 30.00;
                    }
                    $weatherScore = min($weatherScore, 100.00);
                }
            }
        }

        // 2. Ambil data inflasi real-time dari World Bank API
        $inflationScore = 35.00; 
        if ($country->country_code) {
            $isoCode = strtolower($country->country_code);
            $wbResponse = Http::get("https://api.worldbank.org/v2/country/{$isoCode}/indicator/FP.CPI.TOTL.ZG", [
                'format' => 'json',
                'per_page' => 1
            ]);

            if ($wbResponse->successful()) {
                $wbData = $wbResponse->json();
                if (isset($wbData[1][0]['value']) && $wbData[1][0]['value'] !== null) {
                    $rate = $wbData[1][0]['value'];
                    $inflationScore = 20.00;
                    if ($rate > 5.00) {
                        $inflationScore = min(20.00 + ($rate * 10), 100.00);
                    } elseif ($rate < 0) {
                        $inflationScore = 60.00;
                    }
                }
            }
        }

        // 3. Kurs (Currency Risk)
        $currencyScore = 25.00;   

        // 4. Analisis Sentimen Berita Real-time
        $newsSentimentScore = 50.00; 
        $query = urlencode($country->country_name);
        $apiKey = 'YOUR_GNEWS_API_KEY'; 
        $newsResponse = Http::get("https://gnews.io/api/v4/search?q={$query}&lang=en&token={$apiKey}&max=3");

        if ($newsResponse->successful()) {
            $articles = $newsResponse->json()['articles'] ?? [];
            if (count($articles) > 0) {
                $negativeWords = ['crisis', 'strike', 'shortage', 'inflation', 'disaster', 'conflict', 'war', 'risk'];
                $positiveWords = ['growth', 'stable', 'boost', 'recovery', 'profit', 'success'];
                $points = 0;

                foreach ($articles as $art) {
                    $txt = strtolower(($art['title'] ?? '') . ' ' . ($art['description'] ?? ''));
                    $s = 50.00;
                    foreach ($negativeWords as $w) { if (str_contains($txt, $w)) $s += 15.00; }
                    foreach ($positiveWords as $w) { if (str_contains($txt, $w)) $s -= 15.00; }
                    $points += max(0.00, min(100.00, $s));
                }
                $newsSentimentScore = $points / count($articles);
            }
        }

        // 5. Penerapan Weighted Risk Model (Bobot Sesuai Spesifikasi)
        $totalRisk = ($weatherScore * 0.30) + 
                     ($inflationScore * 0.20) + 
                     ($currencyScore * 0.10) + 
                     ($newsSentimentScore * 0.40);

        // 6. Tentukan Risk Level
        $level = 'Low Risk';
        if ($totalRisk >= 40 && $totalRisk < 70) {
            $level = 'Medium Risk';
        } elseif ($totalRisk >= 70) {
            $level = 'High Risk';
        }

        // 7. Simpan atau update ke tabel risk_scores
        RiskScore::updateOrCreate(
            ['country_id' => $countryId],
            [
                'weather_risk' => $weatherScore,
                'inflation_risk' => $inflationScore,
                'currency_risk' => $currencyScore,
                'news_sentiment_risk' => $newsSentimentScore,
                'total_risk_score' => $totalRisk,
                'risk_level' => $level
            ]
        );

        return response()->json([
            'message' => 'Risk score berhasil dikalkulasi dengan seluruh indikator real-time!',
            'country' => $country->country_name,
            'weather_risk' => $weatherScore,
            'inflation_risk' => $inflationScore,
            'news_sentiment_risk' => $newsSentimentScore,
            'total_risk' => $totalRisk,
            'risk_level' => $level
        ]);
    }

    public function fetchWeatherRisk($countryId)
    {
        $country = Country::findOrFail($countryId);

        if (!$country->latitude || !$country->longitude) {
            return response()->json(['message' => 'Koordinat latitude/longitude negara tidak tersedia.'], 400);
        }

        $response = Http::get("https://api.open-meteo.com/v1/forecast", [
            'latitude' => $country->latitude,
            'longitude' => $country->longitude,
            'current_weather' => true,
        ]);

        if ($response->successful()) {
            $weatherData = $response->json()['current_weather'] ?? null;

            if ($weatherData) {
                $temperature = $weatherData['temperature'];
                $windspeed = $weatherData['windspeed'];

                $weatherRiskScore = 10.00;
                
                if ($windspeed > 20) {
                    $weatherRiskScore += 40.00;
                }
                if ($temperature < 0 || $temperature > 35) {
                    $weatherRiskScore += 30.00;
                }

                $weatherRiskScore = min($weatherRiskScore, 100.00);

                return response()->json([
                    'message' => 'Data cuaca berhasil ditarik dari Open-Meteo!',
                    'country' => $country->country_name,
                    'temperature' => $temperature,
                    'windspeed' => $windspeed,
                    'calculated_weather_risk' => $weatherRiskScore
                ]);
            }
        }

        return response()->json(['message' => 'Gagal mengambil data dari Open-Meteo API.'], 500);
    }

    public function fetchInflationRisk($countryId)
    {
        $country = Country::findOrFail($countryId);

        if (!$country->country_code) {
            return response()->json(['message' => 'Kode ISO negara tidak tersedia.'], 400);
        }

        $isoCode = strtolower($country->country_code);
        $response = Http::get("https://api.worldbank.org/v2/country/{$isoCode}/indicator/FP.CPI.TOTL.ZG", [
            'format' => 'json',
            'per_page' => 1
        ]);

        $inflationRate = 3.00; 

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data[1][0]['value']) && $data[1][0]['value'] !== null) {
                $inflationRate = $data[1][0]['value'];
            }
        }

        $inflationRiskScore = 20.00; 
        
        if ($inflationRate > 5.00) {
            $inflationRiskScore = min(20.00 + ($inflationRate * 10), 100.00);
        } elseif ($inflationRate < 0) {
            $inflationRiskScore = 60.00; 
        }

        return response()->json([
            'message' => 'Data inflasi berhasil ditarik dari World Bank API!',
            'country' => $country->country_name,
            'inflation_rate_percent' => $inflationRate,
            'calculated_inflation_risk' => $inflationRiskScore
        ]);
    }

    public function fetchNewsSentiment($countryId)
    {
        $country = Country::findOrFail($countryId);

        $query = urlencode($country->country_name);
        $apiKey = 'YOUR_GNEWS_API_KEY'; 

        $response = Http::get("https://gnews.io/api/v4/search?q={$query}&lang=en&token={$apiKey}&max=5");

        $sentimentScore = 50.00; 
        $articlesCount = 0;

        if ($response->successful()) {
            $articles = $response->json()['articles'] ?? [];
            $negativeWords = ['crisis', 'strike', 'shortage', 'inflation', 'disaster', 'conflict', 'war', 'drop', 'fall', 'risk'];
            $positiveWords = ['growth', 'surging', 'stable', 'boost', 'recovery', 'profit', 'gain', 'success'];

            $totalSentimentPoints = 0;

            foreach ($articles as $article) {
                $text = strtolower(($article['title'] ?? '') . ' ' . ($article['description'] ?? ''));
                $articleScore = 50.00; 

                foreach ($negativeWords as $word) {
                    if (str_contains($text, $word)) {
                        $articleScore += 15.00; 
                    }
                }

                foreach ($positiveWords as $word) {
                    if (str_contains($text, $word)) {
                        $articleScore -= 15.00; 
                    }
                }

                $totalSentimentPoints += max(0.00, min(100.00, $articleScore));
                $articlesCount++;
            }

            if ($articlesCount > 0) {
                $sentimentScore = $totalSentimentPoints / $articlesCount;
            }
        }

        return response()->json([
            'message' => 'Analisis sentimen berita berhasil!',
            'country' => $country->country_name,
            'articles_analyzed' => $articlesCount,
            'calculated_news_sentiment_risk' => $sentimentScore
        ]);
    }
}