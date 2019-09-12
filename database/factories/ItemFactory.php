<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price'=>$faker->numberBetween($min = 1000, $max = 9000),
        'description'=>$faker->sentence($nbWords = 10, $variableNbWords = true),
        'notice'=>$faker->sentence($nbWords = 10, $variableNbWords = true),
        'location_id'=>function(){
            return factory(App\Location::class)->create()->id;
        },
        'merchant_id'=>function(){
            return factory(App\Merchant::class)->create()->id;
        }
    ];
});
