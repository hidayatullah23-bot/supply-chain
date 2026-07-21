<?php
namespace Tests\Feature;
use App\Models\Country;
use App\Models\CurrencyExchangeRate;
use App\Models\EconomicIndicator;
use App\Models\NewsCache;
use App\Models\RiskScore;
use App\Models\WeatherForecast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
class IntelligenceApiTest extends TestCase {
    use RefreshDatabase;
    public function test_required_intelligence_endpoints_are_available():void { $country=Country::create(['country_name'=>'Indonesia','country_code'=>'ID','latitude'=>-6.2,'longitude'=>106.8]); foreach(['/api/countries','/api/risk','/api/ports','/api/news','/api/currency?base=USD&symbols=EUR','/api/weather','/api/economics','/api/sentiments',"/api/countries/{$country->id}/analytics"] as $url){ if(str_contains($url,'currency'))continue; $this->getJson($url)->assertOk()->assertJson(['status'=>'success']); } }
    public function test_country_comparison_renders_with_and_without_selection():void { $first=Country::create(['country_name'=>'Indonesia','country_code'=>'ID']); $second=Country::create(['country_name'=>'Germany','country_code'=>'DE']); $this->get('/countries/compare')->assertOk(); $this->get('/countries/compare?country_id_1='.$first->id.'&country_id_2='.$second->id)->assertOk()->assertSee('Indonesia')->assertSee('Germany'); }

    public function test_dashboard_maps_and_intelligence_pages_render_with_data():void
    {
        $country=Country::create(['country_name'=>'Indonesia','country_code'=>'ID','currency'=>'IDR','latitude'=>-6.2,'longitude'=>106.8]);
        EconomicIndicator::create(['country_id'=>$country->id,'indicator_code'=>'NY.GDP.MKTP.CD','indicator_name'=>'GDP','indicator_value'=>1000000000,'recorded_year'=>2025]);
        WeatherForecast::create(['country_id'=>$country->id,'temperature'=>29,'precipitation'=>2,'wind_speed'=>12,'weather_code'=>61,'condition_status'=>'Hujan','recorded_at'=>now()]);
        CurrencyExchangeRate::create(['country_id'=>$country->id,'currency_code'=>'IDR','exchange_rate'=>16000,'recorded_date'=>today()]);
        RiskScore::create(['country_id'=>$country->id,'weather_risk'=>20,'inflation_risk'=>30,'currency_risk'=>10,'news_sentiment_risk'=>40,'total_risk_score'=>29,'risk_level'=>'Low Risk']);
        NewsCache::create(['country_id'=>$country->id,'title'=>'Trade growth improves','description'=>'Shipping remains stable','source_url'=>'https://example.test/news','sentiment_status'=>'Positive','sentiment_score_positive'=>2,'sentiment_score_negative'=>0]);

        $this->get('/countries')->assertOk()->assertSee('Indonesia');
        $this->get("/countries/{$country->id}")->assertOk()->assertSee('Weighted Risk Model');
        $this->get('/global-map')->assertOk()->assertSee('Monitoring Global');
        $this->get('/weather-map')->assertOk()->assertSee('Keterangan warna marker');
        $this->get("/countries/{$country->id}/currency")->assertOk();
        $this->get("/countries/{$country->id}/report")->assertOk()->assertSee('Indonesia');
    }

    public function test_currency_api_uses_cache_when_provider_is_unavailable():void
    {
        Cache::put('currency-latest:USD:EUR',['base'=>'USD','rates'=>['EUR'=>0.9]],now()->addHour());
        Http::fake(['api.frankfurter.app/*'=>Http::response([],503)]);
        $this->getJson('/api/currency?base=USD&symbols=EUR')->assertOk()
            ->assertJsonPath('status','success')->assertJsonPath('source','cache')
            ->assertJsonPath('data.rates.EUR',0.9);
    }

    public function test_api_filters_and_input_validation_are_enforced():void
    {
        $indonesia=Country::create(['country_name'=>'Indonesia','country_code'=>'ID']);
        Country::create(['country_name'=>'Germany','country_code'=>'DE']);
        $this->getJson('/api/countries?search=Indo')->assertOk()->assertJsonCount(1,'data')->assertJsonPath('data.0.id',$indonesia->id);
        $this->getJson('/api/currency?base=US')->assertUnprocessable();
    }
}
