<?php

use Illuminate\Database\Seeder;
use App\Province;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = ['Kachin', 'Kayar', 'Kayin', 'Chin', 'Mon', 'Burma', 'Rakhine', 'Shan'];
        foreach ($provinces as $province) {
            Province::create(['name' => $province]);
        }
    }
}
