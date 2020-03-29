<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ACategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i< 6; $i++) {
            DB::table('a_categories')->insert(
                ['category_id'=>null, "created_at"=>now(), "updated_at"=>now()]
            );
        }
    }
}
