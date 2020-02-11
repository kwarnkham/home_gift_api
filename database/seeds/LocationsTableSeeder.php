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
        $createdProvince = Province::create(['name' => 'Yangon Province', 'chName' => '仰光省', 'mmName' => 'ရန်ကုန်တိုင်း']);
        Location::create(['name' => 'Yangon', 'chName' => '仰光', 'mmName' => 'ရန်ကုန်မြို့', 'province_id' => $createdProvince->id]);
    }
}
