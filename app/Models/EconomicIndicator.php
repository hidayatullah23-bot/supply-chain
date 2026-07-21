<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class EconomicIndicator extends Model {
    protected $fillable = ['country_id','indicator_name','indicator_code','indicator_value','recorded_year'];
    public function country(){ return $this->belongsTo(Country::class); }
}
