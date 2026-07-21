<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@supplychain.test'],
            ['name' => 'Administrator', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        $this->call([
            CountrySeeder::class,
            PortSeeder::class,
            ArticleSeeder::class,
            SentimentLexiconSeeder::class,
            SupplierSeeder::class,
            WatchlistSeeder::class,
        ]);
    }
}
