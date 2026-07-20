<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Matikan foreign key check sementara agar truncate bisa berjalan
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel terkait berita cache dan countries agar bersih total
        DB::table('news_cache')->truncate();
        Country::truncate();

        // Nyalakan kembali foreign key check
        Schema::enableForeignKeyConstraints();

        $countries = [
            // --- ASIA ---
            ['country_name' => 'Indonesia', 'country_code' => 'ID', 'capital' => 'Jakarta', 'currency' => 'IDR', 'region' => 'Asia', 'population' => 275500000],
            ['country_name' => 'Malaysia', 'country_code' => 'MY', 'capital' => 'Kuala Lumpur', 'currency' => 'MYR', 'region' => 'Asia', 'population' => 33900000],
            ['country_name' => 'Singapore', 'country_code' => 'SG', 'capital' => 'Singapore', 'currency' => 'SGD', 'region' => 'Asia', 'population' => 5900000],
            ['country_name' => 'Thailand', 'country_code' => 'TH', 'capital' => 'Bangkok', 'currency' => 'THB', 'region' => 'Asia', 'population' => 71800000],
            ['country_name' => 'Philippines', 'country_code' => 'PH', 'capital' => 'Manila', 'currency' => 'PHP', 'region' => 'Asia', 'population' => 115500000],
            ['country_name' => 'Vietnam', 'country_code' => 'VN', 'capital' => 'Hanoi', 'currency' => 'VND', 'region' => 'Asia', 'population' => 98100000],
            ['country_name' => 'Brunei', 'country_code' => 'BN', 'capital' => 'Bandar Seri Begawan', 'currency' => 'BND', 'region' => 'Asia', 'population' => 449000],
            ['country_name' => 'Cambodia', 'country_code' => 'KH', 'capital' => 'Phnom Penh', 'currency' => 'KHR', 'region' => 'Asia', 'population' => 16700000],
            ['country_name' => 'Laos', 'country_code' => 'LA', 'capital' => 'Vientiane', 'currency' => 'LAK', 'region' => 'Asia', 'population' => 7500000],
            ['country_name' => 'Myanmar', 'country_code' => 'MM', 'capital' => 'Naypyidaw', 'currency' => 'MMK', 'region' => 'Asia', 'population' => 54100000],
            ['country_name' => 'East Timor', 'country_code' => 'TL', 'capital' => 'Dili', 'currency' => 'USD', 'region' => 'Asia', 'population' => 1340000],
            ['country_name' => 'Japan', 'country_code' => 'JP', 'capital' => 'Tokyo', 'currency' => 'JPY', 'region' => 'Asia', 'population' => 125100000],
            ['country_name' => 'South Korea', 'country_code' => 'KR', 'capital' => 'Seoul', 'currency' => 'KRW', 'region' => 'Asia', 'population' => 51700000],
            ['country_name' => 'China', 'country_code' => 'CN', 'capital' => 'Beijing', 'currency' => 'CNY', 'region' => 'Asia', 'population' => 1412000000],
            ['country_name' => 'India', 'country_code' => 'IN', 'capital' => 'New Delhi', 'currency' => 'INR', 'region' => 'Asia', 'population' => 1428000000],
            ['country_name' => 'Pakistan', 'country_code' => 'PK', 'capital' => 'Islamabad', 'currency' => 'PKR', 'region' => 'Asia', 'population' => 240800000],
            ['country_name' => 'Bangladesh', 'country_code' => 'BD', 'capital' => 'Dhaka', 'currency' => 'BDT', 'region' => 'Asia', 'population' => 171100000],
            ['country_name' => 'Sri Lanka', 'country_code' => 'LK', 'capital' => 'Sri Jayawardenepura Kotte', 'currency' => 'LKR', 'region' => 'Asia', 'population' => 22100000],
            ['country_name' => 'Nepal', 'country_code' => 'NP', 'capital' => 'Kathmandu', 'currency' => 'NPR', 'region' => 'Asia', 'population' => 30500000],
            ['country_name' => 'Maldives', 'country_code' => 'MV', 'capital' => 'Male', 'currency' => 'MVR', 'region' => 'Asia', 'population' => 523000],
            
            // --- TIMUR TENGAH & ASIA BARAT ---
            ['country_name' => 'Saudi Arabia', 'country_code' => 'SA', 'capital' => 'Riyadh', 'currency' => 'SAR', 'region' => 'Middle East', 'population' => 36400000],
            ['country_name' => 'United Arab Emirates', 'country_code' => 'AE', 'capital' => 'Abu Dhabi', 'currency' => 'AED', 'region' => 'Middle East', 'population' => 9400000],
            ['country_name' => 'Qatar', 'country_code' => 'QA', 'capital' => 'Doha', 'currency' => 'QAR', 'region' => 'Middle East', 'population' => 2600000],
            ['country_name' => 'Kuwait', 'country_code' => 'KW', 'capital' => 'Kuwait City', 'currency' => 'KWD', 'region' => 'Middle East', 'population' => 4200000],
            ['country_name' => 'Turkey', 'country_code' => 'TR', 'capital' => 'Ankara', 'currency' => 'TRY', 'region' => 'Europe/Asia', 'population' => 85300000],
            ['country_name' => 'Iran', 'country_code' => 'IR', 'capital' => 'Tehran', 'currency' => 'IRR', 'region' => 'Middle East', 'population' => 88500000],
            ['country_name' => 'Iraq', 'country_code' => 'IQ', 'capital' => 'Baghdad', 'currency' => 'IQD', 'region' => 'Middle East', 'population' => 44400000],
            ['country_name' => 'Jordan', 'country_code' => 'JO', 'capital' => 'Amman', 'currency' => 'JOD', 'region' => 'Middle East', 'population' => 11200000],
            ['country_name' => 'Lebanon', 'country_code' => 'LB', 'capital' => 'Beirut', 'currency' => 'LBP', 'region' => 'Middle East', 'population' => 5400000],
            ['country_name' => 'Oman', 'country_code' => 'OM', 'capital' => 'Muscat', 'currency' => 'OMR', 'region' => 'Middle East', 'population' => 4500000],
            ['country_name' => 'Yemen', 'country_code' => 'YE', 'capital' => 'Sanaa', 'currency' => 'YER', 'region' => 'Middle East', 'population' => 33600000],
            ['country_name' => 'Palestine', 'country_code' => 'PS', 'capital' => 'Jerusalem', 'currency' => 'ILS', 'region' => 'Middle East', 'population' => 5200000],

            // --- EROPA ---
            ['country_name' => 'United Kingdom', 'country_code' => 'GB', 'capital' => 'London', 'currency' => 'GBP', 'region' => 'Europe', 'population' => 67300000],
            ['country_name' => 'Germany', 'country_code' => 'DE', 'capital' => 'Berlin', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 83800000],
            ['country_name' => 'France', 'country_code' => 'FR', 'capital' => 'Paris', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 67900000],
            ['country_name' => 'Italy', 'country_code' => 'IT', 'capital' => 'Rome', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 58800000],
            ['country_name' => 'Spain', 'country_code' => 'ES', 'capital' => 'Madrid', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 47600000],
            ['country_name' => 'Netherlands', 'country_code' => 'NL', 'capital' => 'Amsterdam', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 17700000],
            ['country_name' => 'Belgium', 'country_code' => 'BE', 'capital' => 'Brussels', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 11600000],
            ['country_name' => 'Switzerland', 'country_code' => 'CH', 'capital' => 'Bern', 'currency' => 'CHF', 'region' => 'Europe', 'population' => 8700000],
            ['country_name' => 'Sweden', 'country_code' => 'SE', 'capital' => 'Stockholm', 'currency' => 'SEK', 'region' => 'Europe', 'population' => 10500000],
            ['country_name' => 'Norway', 'country_code' => 'NO', 'capital' => 'Oslo', 'currency' => 'NOK', 'region' => 'Europe', 'population' => 5400000],
            ['country_name' => 'Finland', 'country_code' => 'FI', 'capital' => 'Helsinki', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 5500000],
            ['country_name' => 'Denmark', 'country_code' => 'DK', 'capital' => 'Copenhagen', 'currency' => 'DKK', 'region' => 'Europe', 'population' => 5900000],
            ['country_name' => 'Russia', 'country_code' => 'RU', 'capital' => 'Moscow', 'currency' => 'RUB', 'region' => 'Europe/Asia', 'population' => 143400000],
            ['country_name' => 'Ukraine', 'country_code' => 'UA', 'capital' => 'Kyiv', 'currency' => 'UAH', 'region' => 'Europe', 'population' => 38000000],
            ['country_name' => 'Poland', 'country_code' => 'PL', 'capital' => 'Warsaw', 'currency' => 'PLN', 'region' => 'Europe', 'population' => 37800000],
            ['country_name' => 'Austria', 'country_code' => 'AT', 'capital' => 'Vienna', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 9000000],
            ['country_name' => 'Portugal', 'country_code' => 'PT', 'capital' => 'Lisbon', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 10300000],
            ['country_name' => 'Greece', 'country_code' => 'GR', 'capital' => 'Athens', 'currency' => 'EUR', 'region' => 'Europe', 'population' => 10300000],

            // --- AMERIKA ---
            ['country_name' => 'United States', 'country_code' => 'US', 'capital' => 'Washington D.C.', 'currency' => 'USD', 'region' => 'Americas', 'population' => 333200000],
            ['country_name' => 'Canada', 'country_code' => 'CA', 'capital' => 'Ottawa', 'currency' => 'CAD', 'region' => 'Americas', 'population' => 38900000],
            ['country_name' => 'Mexico', 'country_code' => 'MX', 'capital' => 'Mexico City', 'currency' => 'MXN', 'region' => 'Americas', 'population' => 127500000],
            ['country_name' => 'Brazil', 'country_code' => 'BR', 'capital' => 'Brasilia', 'currency' => 'BRL', 'region' => 'Americas', 'population' => 215300000],
            ['country_name' => 'Argentina', 'country_code' => 'AR', 'capital' => 'Buenos Aires', 'currency' => 'ARS', 'region' => 'Americas', 'population' => 46200000],
            ['country_name' => 'Colombia', 'country_code' => 'CO', 'capital' => 'Bogota', 'currency' => 'COP', 'region' => 'Americas', 'population' => 51800000],
            ['country_name' => 'Chile', 'country_code' => 'CL', 'capital' => 'Santiago', 'currency' => 'CLP', 'region' => 'Americas', 'population' => 19600000],
            ['country_name' => 'Peru', 'country_code' => 'PE', 'capital' => 'Lima', 'currency' => 'PEN', 'region' => 'Americas', 'population' => 34000000],
            ['country_name' => 'Venezuela', 'country_code' => 'VE', 'capital' => 'Caracas', 'currency' => 'VES', 'region' => 'Americas', 'population' => 28300000],

            // --- AFRIKA ---
            ['country_name' => 'Egypt', 'country_code' => 'EG', 'capital' => 'Cairo', 'currency' => 'EGP', 'region' => 'Africa', 'population' => 110900000],
            ['country_name' => 'South Africa', 'country_code' => 'ZA', 'capital' => 'Pretoria', 'currency' => 'ZAR', 'region' => 'Africa', 'population' => 59800000],
            ['country_name' => 'Nigeria', 'country_code' => 'NG', 'capital' => 'Abuja', 'currency' => 'NGN', 'region' => 'Africa', 'population' => 218500000],
            ['country_name' => 'Kenya', 'country_code' => 'KE', 'capital' => 'Nairobi', 'currency' => 'KES', 'region' => 'Africa', 'population' => 54000000],
            ['country_name' => 'Morocco', 'country_code' => 'MA', 'capital' => 'Rabat', 'currency' => 'MAD', 'region' => 'Africa', 'population' => 37400000],
            ['country_name' => 'Algeria', 'country_code' => 'DZ', 'capital' => 'Algiers', 'currency' => 'DZD', 'region' => 'Africa', 'population' => 44900000],
            ['country_name' => 'Ethiopia', 'country_code' => 'ET', 'capital' => 'Addis Ababa', 'currency' => 'ETB', 'region' => 'Africa', 'population' => 123300000],
            ['country_name' => 'Ghana', 'country_code' => 'GH', 'capital' => 'Accra', 'currency' => 'GHS', 'region' => 'Africa', 'population' => 33400000],

            // --- OSEANIA ---
            ['country_name' => 'Australia', 'country_code' => 'AU', 'capital' => 'Canberra', 'currency' => 'AUD', 'region' => 'Oceania', 'population' => 26000000],
            ['country_name' => 'New Zealand', 'country_code' => 'NZ', 'capital' => 'Wellington', 'currency' => 'NZD', 'region' => 'Oceania', 'population' => 5100000],
            ['country_name' => 'Fiji', 'country_code' => 'FJ', 'capital' => 'Suva', 'currency' => 'FJD', 'region' => 'Oceania', 'population' => 926000],
            ['country_name' => 'Papua New Guinea', 'country_code' => 'PG', 'capital' => 'Port Moresby', 'currency' => 'PGK', 'region' => 'Oceania', 'population' => 10100000],
        ];

        $regions = ['Asia', 'Europe', 'Americas', 'Africa', 'Oceania', 'Middle East'];
        $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'CNY', 'IDR'];

        foreach ($countries as $country) {
            Country::create($country);
        }

        $faker = \Faker\Factory::create();
        $countExisted = count($countries);
        
        for ($i = $countExisted + 1; $i <= 250; $i++) {
            $uniqueCode = 'C' . $i;

            Country::create([
                'country_name' => $faker->unique()->country,
                'country_code' => $uniqueCode,
                'capital'      => $faker->city,
                'currency'     => $currencies[array_rand($currencies)],
                'region'       => $regions[array_rand($regions)],
                'population'   => rand(100000, 50000000)
            ]);
        }
    }
}