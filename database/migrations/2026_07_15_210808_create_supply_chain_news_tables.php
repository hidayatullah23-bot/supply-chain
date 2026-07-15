<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Membuat Tabel Kamus Kata (Sentiment Lexicons) terlebih dahulu
        Schema::create('sentiment_lexicons', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->enum('type', ['positive', 'negative']);
            $table->timestamps();
        });

        // 2. Membuat Tabel Cache Berita (News Cache) yang merujuk ke tabel countries
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            // Menghubungkan berita dengan tabel negara yang sudah kamu buat sebelumnya
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_url');
            $table->string('sentiment_status')->default('Neutral'); // Positive, Neutral, Negative
            $table->float('sentiment_score_positive')->default(0);
            $table->float('sentiment_score_negative')->default(0);
            $table->timestamps(); // Digunakan untuk membatasi durasi cache berita[cite: 2]
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop dengan urutan terbalik untuk menghindari error Foreign Key Constraint
        Schema::dropIfExists('news_cache');
        Schema::dropIfExists('sentiment_lexicons');
    }
};