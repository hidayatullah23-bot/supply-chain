<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\RiskScoreHistory;

class RiskScoringService
{
    public function calculate(Country $country): RiskScore
    {
        $weather = $country->weatherForecasts()->latest('recorded_at')->first();
        $inflation = $country->economicIndicators()->where('indicator_code','FP.CPI.TOTL.ZG')->latest('recorded_year')->value('indicator_value');
        $rates = $country->currencyRates()->latest('recorded_date')->limit(30)->pluck('exchange_rate');
        $news = $country->newsCache()->latest()->limit(30)->pluck('sentiment_status');
        $weatherRisk = min(100, (($weather?->wind_speed ?? 10)/50*60) + (($weather?->precipitation ?? 0)/20*40));
        $inflationRisk = min(100, abs((float)($inflation ?? 3))/15*100);
        $currencyRisk = $rates->count() < 2 ? 25 : min(100, (($rates->max()-$rates->min())/max((float)$rates->avg(),.0001))*1000);
        $newsRisk = $news->isEmpty() ? 50 : $news->avg(fn($s)=>$s==='Negative'?100:($s==='Positive'?0:50));
        $total = round($weatherRisk*.30+$inflationRisk*.20+$newsRisk*.40+$currencyRisk*.10,2);
        $level = $total >= 70 ? 'High Risk' : ($total >= 40 ? 'Medium Risk' : 'Low Risk');
        $values = compact('weatherRisk','inflationRisk','currencyRisk','newsRisk','total','level');
        $score = RiskScore::updateOrCreate(['country_id'=>$country->id],[
            'weather_risk'=>$weatherRisk,'inflation_risk'=>$inflationRisk,'currency_risk'=>$currencyRisk,
            'news_sentiment_risk'=>$newsRisk,'total_risk_score'=>$total,'risk_level'=>$level,
        ]);
        RiskScoreHistory::create(['country_id'=>$country->id,'weather_risk'=>$weatherRisk,'inflation_risk'=>$inflationRisk,'currency_risk'=>$currencyRisk,'news_sentiment_risk'=>$newsRisk,'total_risk_score'=>$total,'risk_level'=>$level]);
        return $score;
    }
}
