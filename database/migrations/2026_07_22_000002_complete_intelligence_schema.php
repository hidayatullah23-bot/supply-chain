<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) $table->string('role')->default('user')->after('password');
        });
        Schema::table('articles', function (Blueprint $table) {
            if (! Schema::hasColumn('articles', 'source_url')) $table->string('source_url')->nullable()->after('category');
        });
        Schema::table('ports', function (Blueprint $table) {
            if (! Schema::hasColumn('ports', 'harbor_size')) $table->string('harbor_size')->nullable()->after('longitude');
            if (! Schema::hasColumn('ports', 'source')) $table->string('source')->default('local')->after('harbor_size');
        });
        Schema::table('weather_forecasts', function (Blueprint $table) {
            if (! Schema::hasColumn('weather_forecasts', 'weather_code')) $table->integer('weather_code')->nullable();
            if (! Schema::hasColumn('weather_forecasts', 'recorded_at')) $table->timestamp('recorded_at')->nullable()->index();
        });
        Schema::table('economic_indicators', function (Blueprint $table) {
            $table->decimal('indicator_value', 24, 4)->change();
            if (! Schema::hasColumn('economic_indicators', 'indicator_code')) $table->string('indicator_code')->nullable()->index();
        });
        Schema::create('risk_score_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->decimal('weather_risk', 5, 2);
            $table->decimal('inflation_risk', 5, 2);
            $table->decimal('currency_risk', 5, 2);
            $table->decimal('news_sentiment_risk', 5, 2);
            $table->decimal('total_risk_score', 5, 2);
            $table->string('risk_level');
            $table->timestamps();
            $table->index(['country_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_score_histories');
    }
};
