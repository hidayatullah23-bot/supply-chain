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
} 