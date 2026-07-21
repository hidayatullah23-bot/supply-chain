<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{
    protected $table = 'news_cache';

    protected $fillable = [
        'country_id', 'title', 'description', 'source_url', 'image_url',
        'sentiment_status', 'sentiment_score_positive',
        'sentiment_score_negative',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
