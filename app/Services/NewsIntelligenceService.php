<?php

namespace App\Services;

use App\Models\Country;
use App\Models\NewsCache;
use Illuminate\Support\Facades\Http;

class NewsIntelligenceService
{
    public function __construct(private SentimentService $sentiment) {}

    public function sync(Country $country): array
    {
        $cached = NewsCache::where('country_id', $country->id)->where('created_at', '>=', now()->subHours(6))->latest()->get();
        if ($cached->isNotEmpty() && $cached->whereNotNull('image_url')->count() === $cached->count()) {
            return $cached->all();
        }
        $key = config('services.gnews.key');
        if (! $key) {
            return NewsCache::where('country_id', $country->id)->latest()->limit(20)->get()->all();
        }
        $response = rescue(fn () => Http::timeout(12)->retry(2, 250)->get('https://gnews.io/api/v4/search', ['q' => '(logistics OR trade OR shipping OR economy OR geopolitical) '.$country->country_name, 'lang' => 'en', 'max' => 10, 'apikey' => $key]), null, false);
        if (! $response?->successful()) {
            return NewsCache::where('country_id', $country->id)->latest()->limit(20)->get()->all();
        }
        $articles = $response->json('articles', []);
        $source = 'gnews-topical';
        if ($articles === []) {
            $fallback = rescue(fn () => Http::timeout(12)->retry(2, 250)->get('https://gnews.io/api/v4/search', ['q' => $country->country_name, 'lang' => 'en', 'max' => 10, 'apikey' => $key]), null, false);
            if ($fallback?->successful()) {
                $articles = $fallback->json('articles', []);
                $source = 'gnews-country-fallback';
            }
        }
        foreach ($articles as $article) {
            $analysis = $this->sentiment->analyze(($article['title'] ?? '').' '.($article['description'] ?? ''));
            NewsCache::updateOrCreate(['country_id' => $country->id, 'source_url' => $article['url']], ['title' => $article['title'], 'description' => $article['description'] ?? null, 'image_url' => $article['image'] ?? null, 'sentiment_status' => $analysis['status'], 'sentiment_score_positive' => $analysis['positive'], 'sentiment_score_negative' => $analysis['negative'], 'data_source' => $source, 'is_estimated' => false]);
        }

        return NewsCache::where('country_id', $country->id)->latest()->limit(20)->get()->all();
    }
}
