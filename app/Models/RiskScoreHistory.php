<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RiskScoreHistory extends Model {
    protected $fillable = ['country_id','weather_risk','inflation_risk','currency_risk','news_sentiment_risk','total_risk_score','risk_level'];
    public function country(){ return $this->belongsTo(Country::class); }
}
