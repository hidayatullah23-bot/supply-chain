<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;
use App\Models\User;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan user admin default ada
        User::firstOrCreate(
            ['email' => 'admin@supplychain.com'],
            [
                'name' => 'Admin Supply Chain',
                'password' => bcrypt('password123'),
                'role' => 'admin'
            ]
        );

        // Data utama pelabuhan penting dunia
        $basePorts = [
            ['port_name' => 'Port of Shanghai', 'country_name' => 'China', 'latitude' => 31.2304, 'longitude' => 121.4737],
            ['port_name' => 'Port of Singapore', 'country_name' => 'Singapore', 'latitude' => 1.3521, 'longitude' => 103.8198],
            ['port_name' => 'Port of Rotterdam', 'country_name' => 'Netherlands', 'latitude' => 51.9244, 'longitude' => 4.4777],
            ['port_name' => 'Port of Tanjung Priok', 'country_name' => 'Indonesia', 'latitude' => -6.1054, 'longitude' => 106.8836],
            ['port_name' => 'Port of Surabaya (Tanjung Perak)', 'country_name' => 'Indonesia', 'latitude' => -7.2073, 'longitude' => 112.7358],
        ];

        foreach ($basePorts as $port) {
            Port::updateOrCreate(['port_name' => $port['port_name']], $port + ['source' => 'curated']);
        }
    }
}
