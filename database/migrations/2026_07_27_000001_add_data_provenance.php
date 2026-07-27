<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->json('languages')->nullable()->after('region');
            $table->string('data_source')->default('seed')->after('languages');
            $table->boolean('is_estimated')->default(false)->after('data_source');
        });

        foreach (['weather_forecasts', 'economic_indicators', 'currency_exchange_rates', 'news_cache', 'disruptions'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('data_source')->default('unknown');
                $table->boolean('is_estimated')->default(false);
            });
        }

        DB::table('news_cache')->where('title', 'like', 'Baseline intelijen%')
            ->update(['data_source' => 'internal-baseline', 'is_estimated' => true]);
        DB::table('news_cache')->where('title', 'not like', 'Baseline intelijen%')
            ->update(['data_source' => 'gnews', 'is_estimated' => false]);
        DB::table('economic_indicators')->where('indicator_name', 'like', '%(baseline)%')
            ->update(['data_source' => 'internal-baseline', 'is_estimated' => true]);
        DB::table('economic_indicators')->where('indicator_name', 'not like', '%(baseline)%')
            ->update(['data_source' => 'world-bank', 'is_estimated' => false]);
        DB::table('currency_exchange_rates')->update([
            'data_source' => 'internal-baseline',
            'is_estimated' => true,
        ]);
        DB::table('weather_forecasts')->where('condition_status', 'Baseline estimasi')
            ->update(['data_source' => 'internal-baseline', 'is_estimated' => true]);
        DB::table('weather_forecasts')->where('condition_status', '!=', 'Baseline estimasi')
            ->update(['data_source' => 'open-meteo', 'is_estimated' => false]);
    }

    public function down(): void
    {
        foreach (['weather_forecasts', 'economic_indicators', 'currency_exchange_rates', 'news_cache', 'disruptions'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['data_source', 'is_estimated']);
            });
        }

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn(['languages', 'data_source', 'is_estimated']);
        });
    }
};
