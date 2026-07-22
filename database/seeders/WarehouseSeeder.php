<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            ['SG','SG-SIN-01','Singapore Global Distribution Hub','Jurong Logistics District',185000,'active'],
            ['NL','NL-RTM-01','Rotterdam Gateway Warehouse','Maasvlakte, Rotterdam',240000,'active'],
            ['CN','CN-SHA-01','Shanghai East Logistics Center','Pudong, Shanghai',310000,'active'],
            ['ID','ID-JKT-01','Jakarta Archipelago Hub','Cakung, Jakarta',165000,'active'],
            ['AE','AE-DXB-01','Dubai Intercontinental Hub','Jebel Ali Free Zone',205000,'active'],
            ['US','US-LAX-01','Pacific Coast Fulfillment Center','Long Beach, California',275000,'active'],
            ['DE','DE-HAM-01','Hamburg Northern Europe Hub','Hamburg Port District',198000,'active'],
            ['JP','JP-TYO-01','Tokyo Smart Logistics Hub','Yokohama Bay',172000,'active'],
            ['BR','BR-SAO-01','São Paulo LatAm Distribution Center','Guarulhos, São Paulo',220000,'active'],
            ['ZA','ZA-CPT-01','Cape Town Africa Gateway','Montague Gardens, Cape Town',145000,'inactive'],
        ];

        foreach ($warehouses as [$code, $warehouseCode, $name, $location, $capacity, $status]) {
            $country = Country::where('country_code', $code)->first();
            if (! $country) continue;
            Warehouse::updateOrCreate(['warehouse_code' => $warehouseCode], [
                'country_id' => $country->id,
                'warehouse_name' => $name,
                'location' => $location,
                'capacity_m3' => $capacity,
                'status' => $status,
            ]);
        }
    }
}
