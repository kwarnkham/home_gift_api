<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeliveryFeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i <= 20; $i++) {
            DB::table('delivery_fees')->insert(
                ['fees' => 2000+($i/2*1000), 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()]
            );
        }
        
        DB::table('active_delivery_fees')->insert(
            ['delivery_fees_id' => 1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()]
        );
    }
}
