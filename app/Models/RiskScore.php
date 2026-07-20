<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    protected $table = 'risk_scores';

    protected $fillable = [
        'country_id',
        'weather_risk',
        'inflation_risk',
        'currency_risk',
        'news_sentiment_risk',
        'total_risk_score',
        'risk_level'
    ];

    // Relasi balik ke Model Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}