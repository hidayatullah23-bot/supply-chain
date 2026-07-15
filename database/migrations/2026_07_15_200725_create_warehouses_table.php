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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel negara (countries)
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->string('warehouse_name');
            $table->string('warehouse_code')->unique();
            $table->text('location')->nullable();
            $table->integer('capacity_m3')->comment('Kapasitas dalam meter kubik');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};