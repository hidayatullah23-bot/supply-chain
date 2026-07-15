<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Country;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Ambil semua ID negara yang ada di database
        $countryIds = Country::pluck('id')->toArray();

        if (empty($countryIds)) {
            $this->command->info('Data negara kosong! Jalankan CountrySeeder terlebih dahulu.');
            return;
        }

        // Generate 50 data supplier palsu berkualitas tinggi
        for ($i = 0; $i < 50; $i++) {
            Supplier::create([
                'country_id'    => $faker->randomElement($countryIds),
                'supplier_name' => $faker->company . ' ' . $faker->randomElement(['Ltd', 'Inc', 'Group', 'Logistics', 'Supply']),
                'contact_name'  => $faker->name,
                'email'         => $faker->unique()->companyEmail,
                'phone'         => $faker->phoneNumber,
                'address'       => $faker->address,
                'status'        => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}