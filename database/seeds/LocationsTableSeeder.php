<?php

use Illuminate\Database\Seeder;
use App\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations= array('Yangon', 'Mandalay', 'NPT', 'Lashio', 'Bego', 'MyitKyiNar', 'KyutKai');
        foreach($locations as $location){
            Location::create(['name'=>$location]);
        }
        // factory(App\Location::class, 10)->create();
        
    }
}
