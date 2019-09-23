<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'price'=>$faker->numberBetween($min = 1000, $max = 9000),
        'description'=>$faker->sentence($nbWords = 10, $variableNbWords = true),
        'notice'=>$faker->sentence($nbWords = 10, $variableNbWords = true),
        'weight'=>$faker->numberBetween($min = 1, $max = 20),
        'location_id'=>function(){
            return factory(App\Location::class)->create()->id;
        },
        'merchant_id'=>function(){
            return factory(App\Merchant::class)->create()->id;
        }
    ];
});
