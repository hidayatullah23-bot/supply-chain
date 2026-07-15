<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SentimentLexiconSeeder extends Seeder
{
    public function run(): void
    {
        $positives = [
            'growth', 'increase', 'profit', 'stable', 'improve', 
            'recovery', 'expansion', 'efficient', 'boom', 'surplus',
            'success', 'positive', 'boost', 'strengthen', 'benefit'
        ];

        $negatives = [
            'war', 'crisis', 'inflation', 'delay', 'disaster', 
            'conflict', 'strike', 'shortage', 'decrease', 'deficit',
            'congested', 'protest', 'disruption', 'clash', 'risk'
        ];

        foreach ($positives as $word) {
            DB::table('sentiment_lexicons')->updateOrInsert(
                ['word' => $word],
                ['type' => 'positive', 'created_at' => now(), 'updated_at' => now()]
            );
        }

        foreach ($negatives as $word) {
            DB::table('sentiment_lexicons')->updateOrInsert(
                ['word' => $word],
                ['type' => 'negative', 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}