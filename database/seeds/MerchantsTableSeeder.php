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
        // factory(App\Merchant::class, 10)->create();
        $merchants= array('Myit Myit Khin', 'Shwe PaZun', 'Sein Nagar', 'SP', 'Break Talk', '77Cake');
        foreach($merchants as $merchant){
            App\Merchant::create(['name'=>$merchant]);
        }
    }
}
