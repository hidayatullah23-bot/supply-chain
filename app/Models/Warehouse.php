<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'warehouse_name',
        'warehouse_code',
        'location',
        'capacity_m3',
        'status',
    ];

    /**
     * Relasi: Satu gudang berada di dalam satu negara
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}