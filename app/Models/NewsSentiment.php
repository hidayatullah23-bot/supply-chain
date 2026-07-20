<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsSentiment extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     * (Opsional, jika nama tabelmu adalah 'news_sentiments')
     */
    protected $table = 'news_sentiments';

    /**
     * Kolom yang dapat diisi (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id', 
        'title', 
        'description', 
        'source_url', 
        'sentiment_status', 
        'sentiment_score_positive', 
        'sentiment_score_negative'
    ];

    /**
     * Relasi ke model Country.
     * Setiap record berita sentimen dimiliki oleh satu negara.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}