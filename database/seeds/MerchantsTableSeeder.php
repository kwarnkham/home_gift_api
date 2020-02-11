<?php

use Illuminate\Database\Seeder;

class MerchantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Merchant::create(['name' => 'J\'Donuts', 'ch_name' => 'J\'Donuts', 'mm_name' => 'J\'Donuts']);
    }
}
