<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Supplier;
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

        // Ambil semua ID negara yang ada di database
        $countryIds = Country::pluck('id')->toArray();

        if (empty($countryIds)) {
            $this->command->info('Data negara kosong! Jalankan CountrySeeder terlebih dahulu.');

            return;
        }

        $companyPrefixes = ['Nusantara', 'Pacific', 'Global', 'Maritime', 'Continental', 'Summit', 'Atlas', 'Meridian'];
        $companyTypes = ['Logistics', 'Industries', 'Supply', 'Trading', 'Manufacturing'];
        $contactFirstNames = ['Andi', 'Siti', 'Budi', 'Maya', 'Rizky', 'Dewi', 'Arif', 'Nadia'];
        $contactLastNames = ['Pratama', 'Wijaya', 'Santoso', 'Lestari', 'Hidayat', 'Putri'];

        // Generate 50 data supplier deterministik tanpa dependensi development.
        for ($i = Supplier::count(); $i < 50; $i++) {
            $number = $i + 1;

            Supplier::updateOrCreate(['email' => sprintf('supplier%03d@supplychain.test', $i + 1)], [
                'country_id' => $countryIds[$i % count($countryIds)],
                'supplier_name' => $companyPrefixes[$i % count($companyPrefixes)].' '
                    .$companyTypes[$i % count($companyTypes)].' '.sprintf('%02d', $number),
                'contact_name' => $contactFirstNames[$i % count($contactFirstNames)].' '
                    .$contactLastNames[$i % count($contactLastNames)],
                'phone' => sprintf('+62-21-555-%04d', 1000 + $number),
                'address' => sprintf('Kawasan Industri Blok %s-%02d', chr(65 + ($i % 26)), $number),
                'status' => $i % 10 === 0 ? 'inactive' : 'active',
            ]);
        }
    }
}
