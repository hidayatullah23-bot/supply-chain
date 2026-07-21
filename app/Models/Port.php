<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_name',
        'country_name',
        'latitude',
        'longitude',
        'harbor_size',
        'source',
    ];
}
