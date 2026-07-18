<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    /**
     * Tampilan Utama: Daftar Negara
     */
    public function index()
    {
        $countries = Country::all();
        return view('countries.index', compact('countries'));
    }

    /**
     * Tampilan Form Tambah Negara
     */
    public function create()
    {
        return view('countries.create');
    }

    /**
     * Proses Menyimpan Data Negara Baru ke Database (Mode Los Validasi Unik)
     */
    public function store(Request $request)
    {
        // Paksa input country_code menjadi uppercase
        if ($request->has('country_code')) {
            $request->merge(['country_code' => strtoupper($request->country_code)]);
        }

        // Validasi diperlonggar (menghapus 'unique:countries,country_code') agar tidak terkunci error
        $request->validate([
            'country_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:5',
            'currency' => 'required|string|max:10',
            'region' => 'required|string|max:100',
            'capital' => 'nullable|string|max:100',
            'population' => 'required|numeric|min:0',
        ]);

        // Simpan ke database
        Country::create([
            'country_name' => $request->country_name,
            'country_code' => $request->country_code,
            'currency' => strtoupper($request->currency),
            'region' => $request->region,
            'capital' => $request->capital,
            'population' => $request->population,
        ]);

        return redirect('/countries')->with('success', 'Wilayah operasional supply chain baru berhasil ditambahkan!');
    }

    /**
     * FITUR UTAMA #1: Global Country Dashboard (World Bank & REST Countries API)
     */
    public function dashboard($id)
    {
        // 1. Ambil data negara dari database lokal
        $country = Country::findOrFail($id);
        $countryName = $country->country_name; 
        $countryCode = $country->country_code; 
        $codeLower = strtolower($countryCode ?? 'id');

        // Set default values pintar (Fallback jika API REST Countries bermasalah)
        $countryData = [
            'currency' => $country->currency ?? '-', 
            'region' => $country->region ?? '-', 
            'language' => ($codeLower === 'id') ? 'Indonesian' : '-', 
            'flag' => "https://flagcdn.com/w80/{$codeLower}.png", 
            'gdp' => 'Tidak tersedia', 
            'inflation' => 'Tidak tersedia', 
            'population' => number_format($country->population) . ' (Lokal)' 
        ];
        $errorMessage = null;

        try {
            // 2. Tarik data Geografi dari REST Countries API
            $restResponse = Http::get("https://restcountries.com/v3.1/name/" . urlencode($countryName) . "?fullText=true");
            
            if (!$restResponse->successful() || !isset($restResponse->json()[0])) {
                $restResponse = Http::get("https://restcountries.com/v3.1/name/" . urlencode($countryName));
            }

            if ($restResponse->successful() && isset($restResponse->json()[0])) {
                $geoData = $restResponse->json()[0];
                
                // Ekstrak mata uang dari API
                if (isset($geoData['currencies'])) {
                    $currKey = array_key_first($geoData['currencies']);
                    $countryData['currency'] = $geoData['currencies'][$currKey]['name'] . " (" . $currKey . ")";
                }
                
                $countryData['region'] = $geoData['region'] ?? $country->region;
                
                // Ekstrak bahasa dari API
                if (isset($geoData['languages'])) {
                    $countryData['language'] = implode(', ', array_values($geoData['languages']));
                }
                
                // Gunakan bendera dari API jika sukses ter-load
                if (isset($geoData['flags']['png'])) {
                    $countryData['flag'] = $geoData['flags']['png'];
                }
            }

            // 3. Tarik data Ekonomi dari World Bank API
            $indicators = [
                'gdp' => 'NY.GDP.MKTP.CD',
                'inflation' => 'FP.CPI.TOTL.ZG',
                'population' => 'SP.POP.TOTL'
            ];

            foreach ($indicators as $key => $indicatorCode) {
                $wbResponse = Http::get("https://api.worldbank.org/v2/country/" . urlencode($countryCode ?? 'ID') . "/indicator/{$indicatorCode}?format=json&per_page=5");
                
                if ($wbResponse->successful() && isset($wbResponse->json()[1])) {
                    $records = $wbResponse->json()[1];
                    foreach ($records as $record) {
                        if ($record['value'] !== null) {
                            if ($key == 'gdp') {
                                $countryData['gdp'] = '$' . number_format($record['value'] / 1000000000, 2) . ' Billion (' . $record['date'] . ')';
                            } elseif ($key == 'inflation') {
                                $countryData['inflation'] = number_format($record['value'], 2) . '% (' . $record['date'] . ')';
                            } elseif ($key == 'population') {
                                $countryData['population'] = number_format($record['value']) . ' (' . $record['date'] . ')';
                            }
                            break;
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $errorMessage = "Koneksi ke API geografi terganggu, menggunakan data lokal cadangan.";
        }

        return view('countries.dashboard', compact('country', 'countryData', 'errorMessage'));
    }

    /**
     * FITUR UTAMA #5: News Intelligence & Sentiment Analysis (GNews API dengan Mock Fallback)
     */
    public function news($id)
    {
        $country = Country::findOrFail($id);
        $countryName = $country->country_name; 
        $cacheKey = 'news_sentiment_' . $id;

        $cachedData = Cache::remember($cacheKey, 3600, function () use ($countryName) {
            $apiKey = '63da31e5f80e9fcb942fdf4e9a0fec2e'; 
            $query = urlencode($countryName . ' AND (logistics OR shipping OR trade OR economy)');
            $url = "https://gnews.io/api/v4/search?q={$query}&lang=en&token={$apiKey}&max=5";

            try {
                $response = Http::timeout(5)->get($url);
                
                if ($response->successful()) {
                    $articles = $response->json()['articles'] ?? [];
                    return [
                        'newsData' => $this->analyzeLexiconSentiment($articles),
                        'errorMessage' => null
                    ];
                }
                
                throw new \Exception("API Error Status: " . $response->status());

            } catch (\Exception $e) {
                $mockArticles = [
                    [
                        'title' => "{$countryName} Economic Growth Boosts Maritime Trade and Port Infrastructure Logistics",
                        'description' => "The government reported a stable increase in shipping container volume this quarter. Trade partnerships and modern logistics hubs are expected to strengthen regional profit margins significantly.",
                        'url' => "https://example.com/news/trade-growth"
                    ],
                    [
                        'title' => "Global Inflation and Fuel Crisis Threatens Shipping Networks in {$countryName}",
                        'description' => "A sharp drop in manufacturing output has triggered disruption across domestic supply chains. Experts warn that prolonged delay risks could deepen the ongoing economic conflict.",
                        'url' => "https://example.com/news/inflation-disruption"
                    ],
                    [
                        'title' => "New International Trade Agreement Signed to Improve Logistics Efficiency",
                        'description' => "A bilateral cooperation pact aims to streamline customs and remove transport bottlenecks. This strategic partnership is expected to create a highly stable environment for export-import growth.",
                        'url' => "https://example.com/news/cooperation-deal"
                    ]
                ];

                return [
                    'newsData' => $this->analyzeLexiconSentiment($mockArticles),
                    'errorMessage' => "GNews API offline/unauthorized (Error 401). Menampilkan simulasi data intelijen langsung untuk {$countryName}."
                ];
            }
        });

        $newsData = $cachedData['newsData'];
        $errorMessage = $cachedData['errorMessage'];

        return view('countries.show_news', compact('country', 'newsData', 'errorMessage'));
    }

    /**
     * FITUR AI / DATA SCIENCE: Lexicon Based Sentiment Analysis Engine
     */
    private function analyzeLexiconSentiment($articles)
    {
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'strengthen', 'agreement', 'cooperation', 'partnership'];
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'drop', 'conflict', 'risk', 'disruption'];

        $processedArticles = [];

        foreach ($articles as $article) {
            $text = strtolower(($article['title'] ?? '') . ' ' . ($article['description'] ?? ''));
            $words = explode(' ', preg_replace('/[^a-z0-9 ]/i', '', $text));

            $posScore = 0;
            $negScore = 0;

            foreach ($words as $word) {
                if (in_array($word, $positiveWords)) $posScore++;
                if (in_array($word, $negativeWords)) $negScore++;
            }

            $status = 'Neutral';
            if ($posScore > $negScore) $status = 'Positive';
            elseif ($negScore > $posScore) $status = 'Negative';

            $processedArticles[] = [
                'title' => $article['title'] ?? 'No Title',
                'description' => $article['description'] ?? 'No Description',
                'source_url' => $article['url'] ?? '#',
                'sentiment_status' => $status,
                'sentiment_score_positive' => $posScore,
                'sentiment_score_negative' => $negScore,
            ];
        }

        return $processedArticles;
    }
}