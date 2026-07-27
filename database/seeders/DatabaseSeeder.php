<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@supplychain.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make((string) (env('ADMIN_DEFAULT_PASSWORD') ?: Str::password(32))),
                'role' => 'admin',
            ]
        );

        $this->call([
            CountrySeeder::class,
            PortSeeder::class,
            ArticleSeeder::class,
            SentimentLexiconSeeder::class,
            SupplierSeeder::class,
            WatchlistSeeder::class,
            WarehouseSeeder::class,
            FeatureBaselineSeeder::class,
        ]);
    }
}
