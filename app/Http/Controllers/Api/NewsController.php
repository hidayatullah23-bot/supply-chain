<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsController extends Controller
{
    public function getAnalyzedNews($country_id)
    {
        // 1. Ambil data negara berdasarkan ID
        $country = DB::table('countries')->where('id', $country_id)->first();

        if (!$country) {
            return response()->json([
                'status' => 'error',
                'message' => 'Negara tidak ditemukan di database lokal.'
            ], 404);
        }

        // 2. Cek Cache Berita (apakah sudah ada berita hari ini untuk negara tersebut)
        $today = Carbon::today();
        $cachedNews = DB::table('news_cache')
            ->where('country_id', $country_id)
            ->whereDate('created_at', $today)
            ->get();

        if ($cachedNews->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'source' => 'cache',
                'data' => $cachedNews
            ]);
        }

        // 3. Jika tidak ada cache, ambil data dari API GNews dengan fallback key aman
        $apiKey = env('GNEWS_API_KEY') ?? config('app.gnews_api_key') ?? 'd53a9440957c3f8a534357193d0cf020';
        
        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'GNews API Key belum dikonfigurasi.'
            ], 500);
        }

        // Mengecek apakah kolom 'name' ada, jika tidak ada cari nama kolom lain yang mirip, atau gunakan default
        $countryName = $country->name ?? $country->country_name ?? $country->nama ?? 'Indonesia';

        // Cari berita dengan kata kunci supply chain / logistik yang berkaitan dengan negara tersebut
        $query = '"supply chain" OR "logistics" AND "' . $countryName . '"';
        
        $response = Http::get('https://gnews.io/api/v4/search', [
            'q' => $query,
            'lang' => 'en',
            'apikey' => $apiKey
        ]);

        // Cek jika request ke GNews gagal
        if ($response->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil berita dari API GNews.',
                'error_detail' => $response->json() // Menampilkan alasan detail dari server GNews
            ], 500);
        }

        $articles = $response->json()['articles'] ?? [];

        // 4. Analisis Sentimen Sederhana menggunakan Kamus Kata (Sentiment Lexicon)
        $positives = DB::table('sentiment_lexicons')->where('type', 'positive')->pluck('word')->toArray();
        $negatives = DB::table('sentiment_lexicons')->where('type', 'negative')->pluck('word')->toArray();

        $savedArticles = [];

        foreach ($articles as $article) {
            $title = $article['title'];
            $description = $article['description'] ?? '';
            $textToAnalyze = strtolower($title . ' ' . $description);

            // Hitung skor kata positif dan negatif
            $scorePositive = 0;
            $scoreNegative = 0;

            foreach ($positives as $word) {
                $scorePositive += substr_count($textToAnalyze, $word);
            }

            foreach ($negatives as $word) {
                $scoreNegative += substr_count($textToAnalyze, $word);
            }

            // Tentukan status sentimen akhir
            if ($scorePositive > $scoreNegative) {
                $sentiment = 'Positive';
            } elseif ($scoreNegative > $scorePositive) {
                $sentiment = 'Negative';
            } else {
                $sentiment = 'Neutral';
            }

            // Simpan ke Cache Database
            $newsData = [
                'country_id' => $country_id,
                'title' => $title,
                'description' => $description,
                'source_url' => $article['url'],
                'sentiment_status' => $sentiment,
                'sentiment_score_positive' => $scorePositive,
                'sentiment_score_negative' => $scoreNegative,
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('news_cache')->insert($newsData);
            $savedArticles[] = $newsData;
        }

        return response()->json([
            'status' => 'success',
            'source' => 'api',
            'data' => $savedArticles
        ]);
    }
}