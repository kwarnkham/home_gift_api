<?php

use Illuminate\Database\Seeder;
use App\Location;

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
        $locations = Location::all();
        // $items = ['item 1', 'item 2', 'item 3', 'item 4', 'item 5'];
        foreach ($locations as $location) {
            $temp = App\Item::create([
                'name' => "item from " . $location->name,
                'ch_name' => "item from " . $location->name,
                'mm_name' => "item from " . $location->name,
                'price' => rand(1000, 10000),
                'description' => 'Description of item from ' . $location->name . ' in full information and details',
                'ch_description' => 'Chinese Description of item from ' . $location->name . ' in full information and details',
                'mm_description' => 'Myanmar Description of item from ' . $location->name . ' in full information and details',
                'notice' => 'Notice of item from ' . $location->name . ' if there is',
                'ch_notice' => 'Chinese Notice of item from ' . $location->name . ' if there is',
                'mm_notice' => 'Chinese Notice of item from ' . $location->name . ' if there is',
                'weight' => rand(1, 10) . "kg",
                'location_id' => $location->id,
                'merchant_id' => rand(1, 6)
            ]);
            $temp->categories()->attach(1);
        }
        foreach ($locations as $location) {
            $temp = App\Item::create([
                'name' => "More item from " . $location->name,
                'ch_name' => "More item from " . $location->name,
                'mm_name' => "More item from " . $location->name,
                'price' => rand(1000, 10000),
                'description' => 'Description of item from ' . $location->name . ' in full information and details',
                'ch_description' => 'Chinese Description of item from ' . $location->name . ' in full information and details',
                'mm_description' => 'Myanmar Description of item from ' . $location->name . ' in full information and details',
                'notice' => 'Notice of item from ' . $location->name . ' if there is',
                'ch_notice' => 'Chinese Notice of item from ' . $location->name . ' if there is',
                'mm_notice' => 'Chinese Notice of item from ' . $location->name . ' if there is',
                'weight' => rand(1, 10) . "kg",
                'location_id' => $location->id,
                'merchant_id' => rand(1, 6)
            ]);
            $temp->categories()->attach(1);
        }
    }
}
