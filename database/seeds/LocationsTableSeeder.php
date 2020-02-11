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
        $createdProvince = Province::create(['name' => 'Yangon Province', 'ch_Name' => '仰光省', 'mm_Name' => 'ရန်ကုန်တိုင်း']);
        Location::create(['name' => 'Yangon', 'ch_name' => '仰光', 'mm_name' => 'ရန်ကုန်မြို့', 'province_id' => $createdProvince->id]);
    }
}
