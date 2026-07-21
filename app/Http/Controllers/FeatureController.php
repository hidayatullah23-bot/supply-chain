<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\NewsCache;
use App\Models\Port;
use App\Models\RiskScore;
use App\Models\Supplier;
use App\Models\Warehouse;
class FeatureController extends Controller {
    public function index(string $slug){
        $titles=['ports'=>'Data Pelabuhan Dunia','suppliers'=>'Direktori Supplier','warehouses'=>'Manajemen Gudang','risk-scores'=>'Skor Risiko','articles'=>'Artikel & Berita Krisis','sentiments'=>'Analisis Sentimen Berita'];
        abort_unless(isset($titles[$slug]),404);$title=$titles[$slug];
        if ($slug === 'articles') {
            $newsItems = NewsCache::with('country')->latest()->limit(60)->get();
            $analysisItems = Article::latest()->limit(20)->get();
            $newsStats = [
                'total' => NewsCache::count(),
                'countries' => NewsCache::distinct('country_id')->count('country_id'),
                'positive' => NewsCache::where('sentiment_status','Positive')->count(),
                'negative' => NewsCache::where('sentiment_status','Negative')->count(),
            ];
            return view('feature.articles', compact('title','slug','newsItems','analysisItems','newsStats'));
        }
        $ports=$slug==='ports'?Port::orderBy('port_name')->limit(500)->get():collect();
        $currentData=match($slug){
            'suppliers'=>Supplier::with('country')->latest()->limit(200)->get()->map(fn($x)=>['name'=>$x->supplier_name,'location'=>$x->country?->country_name,'material'=>$x->contact_name,'status'=>$x->status])->all(),
            'warehouses'=>Warehouse::with('country')->latest()->limit(200)->get()->map(fn($x)=>['code'=>$x->warehouse_code,'location'=>$x->location?:$x->country?->country_name,'capacity'=>number_format($x->capacity_m3).' m³','status'=>$x->status])->all(),
            'risk-scores'=>RiskScore::with('country')->orderByDesc('total_risk_score')->get()->map(fn($x)=>['entity'=>$x->country?->country_name,'category'=>'Weather · Inflation · News · Currency','score'=>$x->total_risk_score.' / 100','level'=>$x->risk_level])->all(),
            'articles'=>Article::latest()->get()->map(fn($x)=>['title'=>$x->title,'source'=>$x->category?:'Analisis','date'=>$x->created_at->format('d M Y'),'impact'=>'Informasi'])->all(),
            'sentiments'=>NewsCache::selectRaw('country_id, sentiment_status, COUNT(*) total')->with('country')->groupBy('country_id','sentiment_status')->get()->map(fn($x)=>['topic'=>$x->country?->country_name,'sentiment'=>$x->sentiment_status,'score'=>$x->total.' artikel','trend'=>$x->sentiment_status])->all(),
            default=>[],
        };
        return view('feature.dashboard',compact('title','slug','ports','currentData'));
    }
}
