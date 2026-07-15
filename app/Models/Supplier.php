<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'supplier_name',
        'contact_name',
        'email',
        'phone',
        'address',
        'status'
    ];

    /**
     * Relasi Balik: Seorang supplier berasal dari satu negara
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}