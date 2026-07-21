<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('admin');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // 2. Countries
        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('country_name');
                $table->string('country_code')->nullable();
                $table->decimal('latitude', 10, 6)->nullable();
                $table->decimal('longitude', 10, 6)->nullable();
                $table->timestamps();
            });
        }

        // 3. Ports
        if (!Schema::hasTable('ports')) {
            Schema::create('ports', function (Blueprint $table) {
                $table->id();
                $table->string('port_name');
                $table->string('country_name');
                $table->decimal('latitude', 10, 6)->nullable();
                $table->decimal('longitude', 10, 6)->nullable();
                $table->string('harbor_size')->nullable();
                $table->timestamps();
            });
        }

        // 4. Suppliers
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('supplier_name');
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->string('contact_person')->nullable();
                $table->string('email')->nullable();
                $table->string('status')->default('Active');
                $table->timestamps();
            });
        }

        // 5. Warehouses
        if (!Schema::hasTable('warehouses')) {
            Schema::create('warehouses', function (Blueprint $table) {
                $table->id();
                $table->string('warehouse_name');
                $table->string('location_city');
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->double('capacity_cbm')->nullable();
                $table->timestamps();
            });
        }

        // 6. Risk Scores
        if (!Schema::hasTable('risk_scores')) {
            Schema::create('risk_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->decimal('weather_risk', 5, 2)->default(0);
                $table->decimal('inflation_risk', 5, 2)->default(0);
                $table->decimal('news_risk', 5, 2)->default(0);
                $table->decimal('currency_risk', 5, 2)->default(0);
                $table->decimal('total_risk_score', 5, 2)->default(0);
                $table->string('risk_level')->default('Low Risk');
                $table->timestamps();
            });
        }

        // 7. Articles
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->string('category')->nullable();
                $table->string('source_url')->nullable();
                $table->timestamps();
            });
        }

        // 8. News Sentiments
        if (!Schema::hasTable('news_sentiments')) {
            Schema::create('news_sentiments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
                $table->string('sentiment_label');
                $table->decimal('confidence_score', 5, 2)->nullable();
                $table->timestamps();
            });
        }

        // 9. Watchlists
        if (!Schema::hasTable('watchlists')) {
            Schema::create('watchlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('item_type');
                $table->unsignedBigInteger('item_id');
                $table->timestamps();
            });
        }

        // 10. Shipments
        if (!Schema::hasTable('shipments')) {
            Schema::create('shipments', function (Blueprint $table) {
                $table->id();
                $table->string('tracking_number')->unique();
                $table->unsignedBigInteger('origin_port_id');
                $table->unsignedBigInteger('destination_port_id');
                $table->string('status')->default('In Transit');
                $table->date('estimated_arrival')->nullable();
                $table->timestamps();
            });
        }

        // 11. Weather Forecasts
        if (!Schema::hasTable('weather_forecasts')) {
            Schema::create('weather_forecasts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->decimal('wind_speed', 8, 2)->nullable();
                $table->decimal('temperature', 8, 2)->nullable();
                $table->decimal('precipitation', 8, 2)->nullable();
                $table->string('condition_status')->nullable();
                $table->timestamps();
            });
        }

        // 12. Economic Indicators
        if (!Schema::hasTable('economic_indicators')) {
            Schema::create('economic_indicators', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->string('indicator_name');
                $table->decimal('indicator_value', 12, 4);
                $table->year('recorded_year');
                $table->timestamps();
            });
        }

        // 13. Currency Exchange Rates
        if (!Schema::hasTable('currency_exchange_rates')) {
            Schema::create('currency_exchange_rates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->string('currency_code', 10);
                $table->decimal('exchange_rate', 15, 4);
                $table->date('recorded_date');
                $table->timestamps();
            });
        }

        // 14. Disruptions
        if (!Schema::hasTable('disruptions')) {
            Schema::create('disruptions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->string('severity_level');
                $table->foreignId('affected_country_id')->constrained('countries')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 15. Audit Logs
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->text('action_description');
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();
            });
        }

        // 16. API Logs
        if (!Schema::hasTable('api_logs')) {
            Schema::create('api_logs', function (Blueprint $table) {
                $table->id();
                $table->string('api_endpoint');
                $table->integer('response_status');
                $table->integer('latency_ms');
                $table->timestamps();
            });
        }

        // 17. Notifications
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }

        // 18. Reports
        if (!Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('report_title');
                $table->string('file_path');
                $table->timestamp('generated_date')->useCurrent();
                $table->timestamps();
            });
        }

        // 19. Route Optimizations
        if (!Schema::hasTable('route_optimizations')) {
            Schema::create('route_optimizations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('start_port_id');
                $table->unsignedBigInteger('end_port_id');
                $table->decimal('optimized_distance_nm', 10, 2);
                $table->string('estimated_risk_level');
                $table->timestamps();
            });
        }

        // 20. System Settings
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('setting_key')->unique();
                $table->text('setting_value')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('route_optimizations');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('disruptions');
        Schema::dropIfExists('currency_exchange_rates');
        Schema::dropIfExists('economic_indicators');
        Schema::dropIfExists('weather_forecasts');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('watchlists');
        Schema::dropIfExists('news_sentiments');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('risk_scores');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('suppliers');
    }
};