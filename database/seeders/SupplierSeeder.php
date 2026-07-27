<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Supplier;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Supplier::count() >= 50) {
            return;
        }

        $faker = Faker::create();
        $faker->seed(20260727);

        // Ambil semua ID negara yang ada di database
        $countryIds = Country::pluck('id')->toArray();

        if (empty($countryIds)) {
            $this->command->info('Data negara kosong! Jalankan CountrySeeder terlebih dahulu.');

            return;
        }

        // Generate 50 data supplier palsu berkualitas tinggi
        for ($i = Supplier::count(); $i < 50; $i++) {
            Supplier::updateOrCreate(['email' => sprintf('supplier%03d@supplychain.test', $i + 1)], [
                'country_id' => $faker->randomElement($countryIds),
                'supplier_name' => $faker->company.' '.$faker->randomElement(['Ltd', 'Inc', 'Group', 'Logistics', 'Supply']),
                'contact_name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}
