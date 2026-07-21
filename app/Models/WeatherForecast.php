<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WeatherForecast extends Model {
    protected $fillable = ['country_id','wind_speed','temperature','precipitation','condition_status','weather_code','recorded_at'];
    protected $casts = ['recorded_at'=>'datetime'];
    public function country(){ return $this->belongsTo(Country::class); }
}
