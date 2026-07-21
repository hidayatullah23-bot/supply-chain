<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article; // Sesuaikan dengan nama model artikel Anda (misal: Article / News / Post)

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh data artikel analisis rantai pasok
        $articles = [
            [
                'title' => 'Analisis Dampak Krisis Rantai Pasok Global 2026',
                'content' => 'Kenaikan biaya logistik dan inflasi global memicu perlambatan jalur distribusi utama dunia.',
                'category' => 'Supply Chain Risk'
            ],
            [
                'title' => 'Evaluasi Keamanan Pelabuhan Internasional',
                'content' => 'Peningkatan volume kargo menuntut digitalisasi manajemen pelabuhan agar terhindar dari bottleneck.',
                'category' => 'Port Logistics'
            ],
            [
                'title' => 'Prediksi Risiko Cuaca Ekstrem Terhadap Jalur Maritim',
                'content' => 'Faktor cuaca dan kecepatan angin menjadi variabel dominan yang mempengaruhi ketepatan waktu pengiriman.',
                'category' => 'Weather Impact'
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(['title' => $article['title']], $article);
        }
    }
}