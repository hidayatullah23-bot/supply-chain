<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disruption extends Model
{
    protected $fillable = [
        'title', 'description', 'severity_level', 'affected_country_id',
        'data_source', 'is_estimated',
    ];

    protected $casts = ['is_estimated' => 'boolean'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'affected_country_id');
    }
}
