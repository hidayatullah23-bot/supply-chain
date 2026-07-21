<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\Port;
use App\Models\NewsCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\ExternalIntelligenceService;
use App\Services\RiskScoringService;
use App\Services\NewsIntelligenceService;
use Throwable;

class CountryController extends Controller
{
    public function __construct(
        private ExternalIntelligenceService $intelligence,
        private RiskScoringService $riskScoring,
        private NewsIntelligenceService $newsIntelligence,
    ) {}
    /**
     * Menampilkan Master Dashboard Daftar Negara & Peta Pelabuhan
     */
    public function index()
    {
        $countries = Country::with(['riskScore'])->get();
        $ports = Port::select('id')->get();
        $riskDistribution = [
            'Low Risk' => $countries->filter(fn ($country) => $country->riskScore?->risk_level === 'Low Risk')->count(),
            'Medium Risk' => $countries->filter(fn ($country) => $country->riskScore?->risk_level === 'Medium Risk')->count(),
            'High Risk' => $countries->filter(fn ($country) => $country->riskScore?->risk_level === 'High Risk')->count(),
        ];
        $dashboardStats = [
            'countries' => $countries->count(),
            'ports' => $ports->count(),
            'averageRisk' => round($countries->avg(fn ($country) => (float) ($country->riskScore?->total_risk_score ?? 0)), 1),
            'highRisk' => $riskDistribution['High Risk'],
        ];
        $topRiskCountries = $countries->sortByDesc(fn ($country) => (float) ($country->riskScore?->total_risk_score ?? 0))->take(5)->values();

        return view('countries.index', compact('countries', 'ports', 'riskDistribution', 'dashboardStats', 'topRiskCountries'));
    }

    /**
     * Menampilkan Dashboard Detail Negara
     */
    public function dashboard($id)
    {
        $countryData = Country::with(['riskScore','economicIndicators','weatherForecasts','currencyRates','riskHistory'])->findOrFail($id);
        $latestIndicators = $countryData->economicIndicators->sortByDesc('recorded_year')->unique('indicator_code')->keyBy('indicator_code');
        $weather = $countryData->weatherForecasts->sortByDesc('recorded_at')->first();
        return view('countries.dashboard', compact('countryData','latestIndicators','weather'));
    }

    public function sync($id)
    {
        $country = Country::findOrFail($id);
        try {
            $country = $this->intelligence->syncCountryProfile($country);
            $this->intelligence->syncEconomics($country);
            $this->intelligence->syncWeather($country);
            $this->intelligence->syncCurrency($country);
            $this->riskScoring->calculate($country);
            return back()->with('success','Data REST Countries, World Bank, Open-Meteo, dan kurs berhasil diperbarui.');
        } catch (Throwable $e) {
            report($e); return back()->with('error','Sebagian layanan eksternal tidak tersedia. Data cache tetap ditampilkan.');
        }
    }

    /**
     * Country Comparison Engine (Perbandingan Dua Negara)
     */
    public function compare(Request $request)
    {
        $countries = Country::with(['riskScore'])->get();
        
        $country1 = null;
        $country2 = null;

        if ($request->has('country_id_1') && $request->has('country_id_2')) {
            $country1 = Country::with(['riskScore'])->find($request->country_id_1);
            $country2 = Country::with(['riskScore'])->find($request->country_id_2);
        }

        $comparison = collect([$country1,$country2])->filter()->mapWithKeys(function($country){
            $indicators=$country->economicIndicators()->latest('recorded_year')->get()->unique('indicator_code')->keyBy('indicator_code');
            return [$country->id=>['indicators'=>$indicators,'weather'=>$country->weatherForecasts()->latest('recorded_at')->first(),'rate'=>$country->currencyRates()->latest('recorded_date')->first()]];
        });
        return view('countries.compare', compact('countries', 'country1', 'country2','comparison'));
    }

    /**
     * Currency Impact Dashboard & Chart
     */
    public function currencyChart($id)
    {
        $countryData = Country::findOrFail($id);
        
        try { $this->intelligence->syncCurrency($countryData); } catch (Throwable $e) { report($e); }
        $rates = $countryData->currencyRates()->orderBy('recorded_date')->get();
        $currencyLabels = $rates->pluck('recorded_date')->map->format('d M');
        $currencyValues = $rates->pluck('exchange_rate');

        return view('countries.currency', compact('countryData', 'currencyLabels', 'currencyValues'));
    }

    /**
     * Ekspor Laporan Eksekutif Ringkas
     */
    public function exportReport($id)
    {
        $countryData = Country::with(['riskScore'])->findOrFail($id);
        return view('countries.report', compact('countryData'));
    }

    /**
     * Lexicon-Based Sentiment Analysis Berita
     */
    public function analyzeLexiconSentiment($text)
    {
        $positiveWords = ['growth', 'increase', 'profit', 'stable', 'improve', 'surge', 'success'];
        $negativeWords = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'conflict', 'decline', 'drop'];

        $words = explode(' ', strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $text)));

        $positiveScore = 0;
        $negativeScore = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $positiveScore++;
            }
            if (in_array($word, $negativeWords)) {
                $negativeScore++;
            }
        }

        return $positiveScore > $negativeScore ? "Positive" : ($positiveScore < $negativeScore ? "Negative" : "Neutral");
    }

    /**
     * Weighted Risk Scoring Engine (Kalkulasi Otomatis Risiko Rantai Pasok)
     */
    public function calculateRisk($countryId)
    {
        $country = Country::findOrFail($countryId);
        $this->riskScoring->calculate($country);

        // Diubah agar langsung kembali ke halaman daftar negara membawa pesan sukses
        return redirect()->route('countries.index')->with('success', 'Risk score untuk ' . $country->country_name . ' berhasil dikalkulasi!');
    }

    /**
     * REST API Endpoints Sesuai Spesifikasi Tugas
     */
    public function apiCountries(Request $request)
    { 
        $query = Country::with('riskScore');
        if ($request->filled('search')) {
            $query->where('country_name', 'like', '%'.$request->string('search').'%');
        }
        return response()->json(['status' => 'success', 'data' => $query->get()]);
    }

    public function apiRiskScores() 
    { 
        return response()->json(['status' => 'success', 'data' => RiskScore::with('country')->get()]); 
    }

    public function apiNews(Request $request)
    { 
        $query = NewsCache::with('country')->latest();
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->integer('country_id'));
        }
        return response()->json(['status' => 'success', 'data' => $query->limit(100)->get()]);
    }

    public function weatherMap()
    {
        return view('countries.weather', ['countries'=>Country::with(['weatherForecasts'=>fn($q)=>$q->latest('recorded_at')->limit(1)])->get()]);
    }

    public function apiAnalytics(Country $country)
    {
        return response()->json(['status'=>'success','data'=>[
            'economics'=>$country->economicIndicators()->orderBy('recorded_year')->get()->groupBy('indicator_code'),
            'currency'=>$country->currencyRates()->orderBy('recorded_date')->get(),
            'risk'=>$country->riskHistory()->orderBy('created_at')->get(),
            'weather'=>$country->weatherForecasts()->latest('recorded_at')->first(),
        ]]);
    }

    public function apiWeather(Request $request)
    {
        return response()->json(['status'=>'success','data'=>Country::with(['weatherForecasts'=>fn($q)=>$q->latest('recorded_at')->limit(1)])->when($request->filled('country_id'),fn($q)=>$q->whereKey($request->integer('country_id')))->get()]);
    }

    public function apiEconomics(Request $request)
    {
        $query=\App\Models\EconomicIndicator::with('country')->orderBy('recorded_year');
        if($request->filled('country_id'))$query->where('country_id',$request->integer('country_id'));
        if($request->filled('indicator'))$query->where('indicator_code',$request->string('indicator'));
        return response()->json(['status'=>'success','data'=>$query->get()]);
    }

    public function apiSentiments(Request $request)
    {
        $query=NewsCache::with('country')->latest(); if($request->filled('country_id'))$query->where('country_id',$request->integer('country_id'));
        return response()->json(['status'=>'success','summary'=>(clone $query)->selectRaw('sentiment_status, count(*) total')->groupBy('sentiment_status')->pluck('total','sentiment_status'),'data'=>$query->limit(100)->get()]);
    }

    public function globalMap()
    {
        return view('countries.map', [
            'countryCount' => Country::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'portCount' => Port::whereNotNull('latitude')->whereNotNull('longitude')->count(),
        ]);
    }

    public function apiCurrency(Request $request)
    {
        $validated = $request->validate([
            'base' => ['nullable', 'string', 'size:3'],
            'symbols' => ['nullable', 'string', 'max:100'],
        ]);
        $base = strtoupper($validated['base'] ?? 'USD');
        $symbols = isset($validated['symbols']) ? strtoupper($validated['symbols']) : null;
        $cacheKey = 'currency-latest:'.$base.':'.($symbols ?: 'all');
        $data = Cache::get($cacheKey);
        $source = 'cache';

        try {
            $response = Http::timeout(10)->retry(2, 200)->get(
                'https://api.frankfurter.app/latest',
                array_filter(['from' => $base, 'to' => $symbols])
            );
            if ($response->successful()) {
                $data = $response->json();
                Cache::put($cacheKey, $data, now()->addHours(12));
                $source = 'Frankfurter';
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        if (! $data) {
            return response()->json(['status'=>'error','message'=>'Layanan kurs sedang tidak tersedia dan cache belum tersedia.'], 503);
        }
        return response()->json(['status'=>'success','source'=>$source,'data'=>$data]);
    }
    /**
     * Menampilkan Halaman Berita & Analisis Sentimen Negara
     */
    public function news($id)
    {
        $countryData = Country::findOrFail($id);
        try { $this->newsIntelligence->sync($countryData); } catch(Throwable $e) { report($e); }
        $newsList = NewsCache::where('country_id',$id)->latest()->limit(50)->get();
        $total=max(1,$newsList->count());
        $sentimentSummary=collect(['Positive','Neutral','Negative'])->mapWithKeys(fn($label)=>[$label=>round($newsList->where('sentiment_status',$label)->count()/$total*100,1)]);
        $countryOptions = Country::orderBy('country_name')->get(['id','country_name','country_code']);
        return view('countries.news', compact('countryData', 'newsList','sentimentSummary','countryOptions'));
    }
}
