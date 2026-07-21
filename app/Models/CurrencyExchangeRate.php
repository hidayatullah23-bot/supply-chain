<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CurrencyExchangeRate extends Model {
    protected $fillable = ['country_id','currency_code','exchange_rate','recorded_date'];
    protected $casts = ['recorded_date'=>'date'];
    public function country(){ return $this->belongsTo(Country::class); }
}
