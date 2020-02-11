<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Category::class, 10)->create();
        App\Category::create(['name' => 'Cookie', 'ch_name' => '曲奇饼', 'mm_name' => 'ကွတ်ကီး']);
    }
}
