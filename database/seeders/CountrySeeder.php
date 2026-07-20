<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['country_name' => 'Afghanistan', 'country_code' => 'AF', 'latitude' => 33.9391, 'longitude' => 67.7100],
            ['country_name' => 'Albania', 'country_code' => 'AL', 'latitude' => 41.1533, 'longitude' => 20.1683],
            ['country_name' => 'Algeria', 'country_code' => 'DZ', 'latitude' => 28.0339, 'longitude' => 1.6596],
            ['country_name' => 'Andorra', 'country_code' => 'AD', 'latitude' => 42.5063, 'longitude' => 1.5218],
            ['country_name' => 'Angola', 'country_code' => 'AO', 'latitude' => -11.2027, 'longitude' => 17.8739],
            ['country_name' => 'Argentina', 'country_code' => 'AR', 'latitude' => -38.4161, 'longitude' => -63.6167],
            ['country_name' => 'Armenia', 'country_code' => 'AM', 'latitude' => 40.0691, 'longitude' => 45.0382],
            ['country_name' => 'Australia', 'country_code' => 'AU', 'latitude' => -25.2744, 'longitude' => 133.7751],
            ['country_name' => 'Austria', 'country_code' => 'AT', 'latitude' => 47.5162, 'longitude' => 14.5501],
            ['country_name' => 'Azerbaijan', 'country_code' => 'AZ', 'latitude' => 40.1431, 'longitude' => 47.5769],
            ['country_name' => 'Bahamas', 'country_code' => 'BS', 'latitude' => 25.0343, 'longitude' => -77.3963],
            ['country_name' => 'Bahrain', 'country_code' => 'BH', 'latitude' => 26.0667, 'longitude' => 50.5577],
            ['country_name' => 'Bangladesh', 'country_code' => 'BD', 'latitude' => 23.6850, 'longitude' => 90.3563],
            ['country_name' => 'Barbados', 'country_code' => 'BB', 'latitude' => 13.1939, 'longitude' => -59.5432],
            ['country_name' => 'Belarus', 'country_code' => 'BY', 'latitude' => 53.7098, 'longitude' => 27.9534],
            ['country_name' => 'Belgium', 'country_code' => 'BE', 'latitude' => 50.5039, 'longitude' => 4.4699],
            ['country_name' => 'Belize', 'country_code' => 'BZ', 'latitude' => 17.1899, 'longitude' => -88.4976],
            ['country_name' => 'Benin', 'country_code' => 'BJ', 'latitude' => 9.3077, 'longitude' => 2.3158],
            ['country_name' => 'Bhutan', 'country_code' => 'BT', 'latitude' => 27.5142, 'longitude' => 90.4336],
            ['country_name' => 'Bolivia', 'country_code' => 'BO', 'latitude' => -16.2902, 'longitude' => -63.5887],
            ['country_name' => 'Bosnia and Herzegovina', 'country_code' => 'BA', 'latitude' => 43.9159, 'longitude' => 17.6791],
            ['country_name' => 'Botswana', 'country_code' => 'BW', 'latitude' => -22.3285, 'longitude' => 24.6849],
            ['country_name' => 'Brazil', 'country_code' => 'BR', 'latitude' => -14.2350, 'longitude' => -51.9253],
            ['country_name' => 'Brunei', 'country_code' => 'BN', 'latitude' => 4.5353, 'longitude' => 114.7277],
            ['country_name' => 'Bulgaria', 'country_code' => 'BG', 'latitude' => 42.7339, 'longitude' => 25.4858],
            ['country_name' => 'Cambodia', 'country_code' => 'KH', 'latitude' => 12.5657, 'longitude' => 104.9910],
            ['country_name' => 'Cameroon', 'country_code' => 'CM', 'latitude' => 3.8480, 'longitude' => 11.5021],
            ['country_name' => 'Canada', 'country_code' => 'CA', 'latitude' => 56.1304, 'longitude' => -106.3468],
            ['country_name' => 'Chile', 'country_code' => 'CL', 'latitude' => -35.6751, 'longitude' => -71.5430],
            ['country_name' => 'China', 'country_code' => 'CN', 'latitude' => 35.8617, 'longitude' => 104.1954],
            ['country_name' => 'Colombia', 'country_code' => 'CO', 'latitude' => 4.5709, 'longitude' => -74.2973],
            ['country_name' => 'Croatia', 'country_code' => 'HR', 'latitude' => 45.1000, 'longitude' => 15.2000],
            ['country_name' => 'Cuba', 'country_code' => 'CU', 'latitude' => 21.5218, 'longitude' => -77.7812],
            ['country_name' => 'Cyprus', 'country_code' => 'CY', 'latitude' => 35.1264, 'longitude' => 33.4299],
            ['country_name' => 'Czech Republic', 'country_code' => 'CZ', 'latitude' => 49.8175, 'longitude' => 15.4730],
            ['country_name' => 'Denmark', 'country_code' => 'DK', 'latitude' => 56.2639, 'longitude' => 9.5018],
            ['country_name' => 'Ecuador', 'country_code' => 'EC', 'latitude' => -1.8312, 'longitude' => -78.1834],
            ['country_name' => 'Egypt', 'country_code' => 'EG', 'latitude' => 26.8206, 'longitude' => 30.8025],
            ['country_name' => 'Estonia', 'country_code' => 'EE', 'latitude' => 58.5953, 'longitude' => 25.0136],
            ['country_name' => 'Ethiopia', 'country_code' => 'ET', 'latitude' => 9.1450, 'longitude' => 40.4897],
            ['country_name' => 'Fiji', 'country_code' => 'FJ', 'latitude' => -17.7134, 'longitude' => 178.0650],
            ['country_name' => 'Finland', 'country_code' => 'FI', 'latitude' => 61.9241, 'longitude' => 25.7482],
            ['country_name' => 'France', 'country_code' => 'FR', 'latitude' => 46.2276, 'longitude' => 2.2137],
            ['country_name' => 'Germany', 'country_code' => 'DE', 'latitude' => 51.1657, 'longitude' => 10.4515],
            ['country_name' => 'Greece', 'country_code' => 'GR', 'latitude' => 39.0742, 'longitude' => 21.8243],
            ['country_name' => 'Hong Kong', 'country_code' => 'HK', 'latitude' => 22.3193, 'longitude' => 114.1694],
            ['country_name' => 'Hungary', 'country_code' => 'HU', 'latitude' => 47.1625, 'longitude' => 19.5033],
            ['country_name' => 'India', 'country_code' => 'IN', 'latitude' => 20.5937, 'longitude' => 78.9629],
            ['country_name' => 'Indonesia', 'country_code' => 'ID', 'latitude' => -0.7893, 'longitude' => 113.9213],
            ['country_name' => 'Iran', 'country_code' => 'IR', 'latitude' => 32.4279, 'longitude' => 53.6880],
            ['country_name' => 'Iraq', 'country_code' => 'IQ', 'latitude' => 33.2232, 'longitude' => 43.6793],
            ['country_name' => 'Ireland', 'country_code' => 'IE', 'latitude' => 53.1424, 'longitude' => -7.6921],
            ['country_name' => 'Israel', 'country_code' => 'IL', 'latitude' => 31.0461, 'longitude' => 34.8516],
            ['country_name' => 'Italy', 'country_code' => 'IT', 'latitude' => 41.8719, 'longitude' => 12.5674],
            ['country_name' => 'Japan', 'country_code' => 'JP', 'latitude' => 36.2048, 'longitude' => 138.2529],
            ['country_name' => 'Jordan', 'country_code' => 'JO', 'latitude' => 30.5852, 'longitude' => 36.2384],
            ['country_name' => 'Kazakhstan', 'country_code' => 'KZ', 'latitude' => 48.0196, 'longitude' => 66.9237],
            ['country_name' => 'Kenya', 'country_code' => 'KE', 'latitude' => -0.0236, 'longitude' => 37.9062],
            ['country_name' => 'Kuwait', 'country_code' => 'KW', 'latitude' => 29.3117, 'longitude' => 47.4818],
            ['country_name' => 'Malaysia', 'country_code' => 'MY', 'latitude' => 4.2105, 'longitude' => 101.9758],
            ['country_name' => 'Mexico', 'country_code' => 'MX', 'latitude' => 23.6345, 'longitude' => -102.5528],
            ['country_name' => 'Netherlands', 'country_code' => 'NL', 'latitude' => 52.1326, 'longitude' => 5.2913],
            ['country_name' => 'New Zealand', 'country_code' => 'NZ', 'latitude' => -40.9006, 'longitude' => 174.8860],
            ['country_name' => 'Nigeria', 'country_code' => 'NG', 'latitude' => 9.0820, 'longitude' => 8.6753],
            ['country_name' => 'Norway', 'country_code' => 'NO', 'latitude' => 60.4720, 'longitude' => 8.4689],
            ['country_name' => 'Pakistan', 'country_code' => 'PK', 'latitude' => 30.3753, 'longitude' => 69.3451],
            ['country_name' => 'Philippines', 'country_code' => 'PH', 'latitude' => 12.8797, 'longitude' => 121.7740],
            ['country_name' => 'Poland', 'country_code' => 'PL', 'latitude' => 51.9194, 'longitude' => 19.1451],
            ['country_name' => 'Portugal', 'country_code' => 'PT', 'latitude' => 39.3999, 'longitude' => -8.2245],
            ['country_name' => 'Qatar', 'country_code' => 'QA', 'latitude' => 25.3548, 'longitude' => 51.1839],
            ['country_name' => 'Russia', 'country_code' => 'RU', 'latitude' => 61.5240, 'longitude' => 105.3188],
            ['country_name' => 'Saudi Arabia', 'country_code' => 'SA', 'latitude' => 23.8859, 'longitude' => 45.0792],
            ['country_name' => 'Singapore', 'country_code' => 'SG', 'latitude' => 1.3521, 'longitude' => 103.8198],
            ['country_name' => 'South Africa', 'country_code' => 'ZA', 'latitude' => -30.5595, 'longitude' => 22.9375],
            ['country_name' => 'South Korea', 'country_code' => 'KR', 'latitude' => 35.9078, 'longitude' => 127.7669],
            ['country_name' => 'Spain', 'country_code' => 'ES', 'latitude' => 40.4637, 'longitude' => -3.7492],
            ['country_name' => 'Sweden', 'country_code' => 'SE', 'latitude' => 60.1282, 'longitude' => 18.6435],
            ['country_name' => 'Switzerland', 'country_code' => 'CH', 'latitude' => 46.8182, 'longitude' => 8.2275],
            ['country_name' => 'Taiwan', 'country_code' => 'TW', 'latitude' => 23.6978, 'longitude' => 120.9605],
            ['country_name' => 'Thailand', 'country_code' => 'TH', 'latitude' => 15.8700, 'longitude' => 100.9925],
            ['country_name' => 'Turkey', 'country_code' => 'TR', 'latitude' => 38.9637, 'longitude' => 35.2433],
            ['country_name' => 'Ukraine', 'country_code' => 'UA', 'latitude' => 48.3794, 'longitude' => 31.1656],
            ['country_name' => 'United Arab Emirates', 'country_code' => 'AE', 'latitude' => 23.4241, 'longitude' => 53.8478],
            ['country_name' => 'United Kingdom', 'country_code' => 'GB', 'latitude' => 55.3781, 'longitude' => -3.4360],
            ['country_name' => 'United States', 'country_code' => 'US', 'latitude' => 37.0902, 'longitude' => -95.7129],
            ['country_name' => 'Vietnam', 'country_code' => 'VN', 'latitude' => 14.0583, 'longitude' => 108.2772]
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['country_code' => $country['country_code']],
                $country
            );
        }
    }
}