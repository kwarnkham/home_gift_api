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
        $item = App\Item::create([
            'name' => 'Pineapple Cookie',
            'ch_name' => '菠萝饼干',
            'mm_name' => "နာနတ်သီးကွတ်ကီး",
            'price' => 1000,
            'description' => 'Pineapple Cookie produced by J\'Donuts. Ingredients - Pineapple Jam, Milk, Sugar, Egg, Wheat Flour.',
            'ch_description' => 'J\'Donuts饼庄生产的菠萝饼干。 配料-菠萝果酱，牛奶，糖，鸡蛋，小麦粉',
            'mm_description' => 'J\'donuts Bakery မှထုတ်လုပ်သောနာနတ်သီးဘီစကွတ်။ ပါဝင်ပစ္စည်းများ - နာနတ်သီးယို, နို့, သကြား, ကြက်ဥ, ဂျုံမှုန့်။',
            'notice' => 'Shelf life: 1 week',
            'ch_notice' => '保质期1个礼拜',
            'mm_notice' => 'သက်တမ်း - ၁ ပတ်',
            'weight' => '100g',
            'location_id' => 1,
            'merchant_id' => 1
        ]);
        $item->categories()->attach(1);
    }
}
