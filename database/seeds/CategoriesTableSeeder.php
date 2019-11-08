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
        $categories = array('Cake', 'Drink', 'Fried', 'Meat', 'Sweet', 'Diary');
        foreach ($categories as $category) {
            App\Category::create(['name' => $category]);
        }
    }
}
