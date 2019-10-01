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
        // factory(App\Item::class, 10)->create()->each(function ($item) {
        //     $item->categories()->save(factory(App\Category::class)->make());
        // });
        $items = ['item 1', 'item 2', 'item 3', 'item 4', 'item 5'];
        foreach($items as $item){
            $temp =App\Item::create([
                'name'=>$item,
                'price'=>rand(1000, 10000),
                'description'=>'Description of '.$item.' in full information and details',
                'notice'=>'Notice of '.$item.' if there is',
                'weight'=>rand(1,10),
                'location_id'=>rand(1,7),
                'merchant_id'=>rand(1,6)
            ]);
            $temp->categories()->attach(1);
        }
    }
}
