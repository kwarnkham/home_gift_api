<?php

use Illuminate\Database\Seeder;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images= array('image-1.jpg', 'image-2.jpg', 'image-3.jpg', 'image-4.jpg', 'image-5.jpg');
        foreach($images as $image){
            App\Image::create([
                'name'=>$image,
                'item_id'=>rand(1,2)
                ]);
        }
    }
}
