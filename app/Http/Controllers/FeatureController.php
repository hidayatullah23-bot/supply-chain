<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Country;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\Supplier;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $titles = [
            'ports' => 'Data Pelabuhan Dunia',
            'suppliers' => 'Direktori Supplier',
            'risk-scores' => 'Skor Risiko',
            'articles' => 'Artikel & Berita Krisis',
            'sentiments' => 'Analisis Sentimen Berita',
        ];
        abort_unless(isset($titles[$slug]), 404);
        $title = $titles[$slug];

        if ($slug === 'ports') {
            $search = trim((string) $request->query('search'));
            $ports = Port::query()
                ->when($search, fn ($query) => $query->where(fn ($query) => $query
                    ->where('port_name', 'like', "%{$search}%")
                    ->orWhere('country_name', 'like', "%{$search}%")
                    ->orWhere('harbor_size', 'like', "%{$search}%")))
                ->orderBy('port_name')->paginate(20)->withQueryString();
            $stats = [
                'total' => Port::count(),
                'countries' => Port::distinct()->count('country_name'),
                'large' => Port::whereIn('harbor_size', ['Large', 'Very Large'])->count(),
                'regions' => Port::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            ];
            $sizes = Port::selectRaw("COALESCE(harbor_size, 'Unclassified') label, COUNT(*) total")
                ->groupBy('harbor_size')->orderByDesc('total')->limit(6)->pluck('total', 'label');
            $countryCodes = Country::whereIn('country_name', $ports->pluck('country_name')->filter()->unique())
                ->pluck('country_code', 'country_name');
            return view('ports.index', compact('ports', 'stats', 'sizes', 'search', 'countryCodes'));
        }

        if ($slug === 'suppliers') {
            $search = trim((string) $request->query('search'));
            $suppliers = Supplier::with('country')
                ->when($search, fn ($query) => $query->where(fn ($query) => $query
                    ->where('supplier_name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('country', fn ($country) => $country->where('country_name', 'like', "%{$search}%"))))
                ->latest()->paginate(15)->withQueryString();
            $total = Supplier::count();
            $active = Supplier::where('status', 'active')->count();
            $stats = [
                'total' => $total,
                'active' => $active,
                'countries' => Supplier::distinct()->count('country_id'),
                'coverage' => $total ? round($active / $total * 100) : 0,
            ];
            $regions = Supplier::with('country')->get()->groupBy(fn ($supplier) => $supplier->country?->region ?? 'Other')
                ->map->count()->sortDesc()->take(6);
            return view('suppliers.dashboard', compact('suppliers', 'stats', 'regions', 'search'));
        }

        if ($slug === 'articles') {
            $newsItems = NewsCache::with('country')->latest()->limit(60)->get();
            $analysisItems = Article::latest()->limit(20)->get();
            $newsStats = [
                'total' => NewsCache::count(),
                'countries' => NewsCache::distinct('country_id')->count('country_id'),
                'positive' => NewsCache::where('sentiment_status', 'Positive')->count(),
                'negative' => NewsCache::where('sentiment_status', 'Negative')->count(),
            ];
            return view('feature.articles', compact('title', 'slug', 'newsItems', 'analysisItems', 'newsStats'));
        }

        $currentData = match ($slug) {
            'risk-scores' => RiskScore::with('country')->orderByDesc('total_risk_score')->get()
                ->map(fn ($score) => [
                    'entity' => $score->country?->country_name,
                    'category' => 'Weather · Inflation · News · Currency',
                    'score' => $score->total_risk_score.' / 100',
                    'level' => $score->risk_level,
                ])->all(),
            'sentiments' => NewsCache::selectRaw('country_id, sentiment_status, COUNT(*) total')->with('country')
                ->groupBy('country_id', 'sentiment_status')->get()
                ->map(fn ($item) => [
                    'topic' => $item->country?->country_name,
                    'sentiment' => $item->sentiment_status,
                    'score' => $item->total.' artikel',
                    'trend' => $item->sentiment_status,
                ])->all(),
        };

        return view('feature.dashboard', compact('title', 'slug', 'currentData'));
    }
}
