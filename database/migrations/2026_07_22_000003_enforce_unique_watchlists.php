<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('watchlists')
            ->select('user_id', 'country_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'country_id')
            ->havingRaw('COUNT(*) > 1')
            ->orderBy('keep_id')
            ->each(function ($duplicate) {
                DB::table('watchlists')
                    ->where('user_id', $duplicate->user_id)
                    ->where('country_id', $duplicate->country_id)
                    ->where('id', '<>', $duplicate->keep_id)
                    ->delete();
            });

        $hasUnique = collect(Schema::getIndexes('watchlists'))->contains(function ($index) {
            return $index['unique'] && $index['columns'] === ['user_id', 'country_id'];
        });

        if (! $hasUnique) {
            Schema::table('watchlists', fn (Blueprint $table) => $table->unique(['user_id', 'country_id']));
        }
    }

    public function down(): void {}
};
