<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'country_name', 
        'country_code', 
        'capital', 
        'currency', 
        'region', 
        'population', 
        'latitude', 
        'longitude'
    ];

    public function newsCache()
    {
        return $this->hasMany(NewsCache::class, 'country_id');
    } // Kurung kurawal penutup newsCache sudah benar di sini

    public function riskScore()
    {
        return $this->hasOne(RiskScore::class, 'country_id');
    }
}