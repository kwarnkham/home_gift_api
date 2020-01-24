<?php

use Illuminate\Database\Seeder;
use App\Location;
use App\Province;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = ['Ayeyarwady', 'Bago', 'Chin', 'Kachin', 'Kayah', 'Kayin', 'Magway', 'Mandalay', 'Mon', 'Rakhine', 'Shan', 'Sagaing', 'Tanintharyi', 'Yangon'];

        foreach ($provinces as $province) {
            $createdProvince = Province::create(['name' => $province]);
            // Location::create(['name' => $province, 'province_id' => $createdProvince->id]);
        }
    }
}
