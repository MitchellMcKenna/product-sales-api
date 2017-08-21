<?php

use App\Order;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'order_id' => $faker->randomNumber(),
        'quantity' => $faker->numberBetween(1, 30),
        'product_id' => function () {
            return factory(Product::class)->create()->id;
        }
    ];
});
