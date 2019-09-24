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
        $categories= array('C1', 'C2', 'C3', 'C4', 'C5', 'C6');
        foreach($categories as $category){
            App\Category::create(['name'=>$category]);
        }
    }
}
