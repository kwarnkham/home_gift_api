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
        $images = array('image-1.jpg', 'image-2.jpg', 'image-3.jpg', 'image-4.jpg', 'image-5.jpg','image-6.jpg', 'image-7.jpg', 'image-8.jpg', 'image-9.jpg', 'image-10.jpg','image-11.jpg', 'image-12.jpg', 'image-13.jpg', 'image-14.jpg');
        foreach ($images as $key => $image) {
            App\Image::create([
                'name' => $image,
                'item_id' => $key + 1
            ]);
        }
    }
}
