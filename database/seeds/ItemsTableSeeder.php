<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\Item::class, 10)->create();  
        factory(App\Item::class, 10)->create()->each(function ($item) {
            $item->categories()->save(factory(App\Category::class)->make());
        });
    }
}
