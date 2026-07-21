<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SentimentService
{
    public function analyze(string $text): array
    {
        $words = preg_split('/[^\pL\pN]+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
        $lexicons = DB::table('sentiment_lexicons')->get()->groupBy('type');
        $positive = array_count_values($lexicons->get('positive', collect())->pluck('word')->all());
        $negative = array_count_values($lexicons->get('negative', collect())->pluck('word')->all());
        $positiveScore = $negativeScore = 0;
        foreach ($words as $word) {
            $positiveScore += isset($positive[$word]) ? 1 : 0;
            $negativeScore += isset($negative[$word]) ? 1 : 0;
        }
        $status = $positiveScore > $negativeScore ? 'Positive' : ($negativeScore > $positiveScore ? 'Negative' : 'Neutral');
        return ['status'=>$status, 'positive'=>$positiveScore, 'negative'=>$negativeScore];
    }
}
