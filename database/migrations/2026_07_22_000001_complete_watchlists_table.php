<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            if (! Schema::hasColumn('watchlists', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('watchlists', 'country_id')) {
                $table->foreignId('country_id')->after('user_id')->constrained()->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            if (Schema::hasColumn('watchlists', 'country_id')) {
                $table->dropConstrainedForeignId('country_id');
            }
            if (Schema::hasColumn('watchlists', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
