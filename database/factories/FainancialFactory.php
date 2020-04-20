<?php

use Faker\Factory;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Str;

$faker = Factory::create('fa_IR');


$factory->define(App\Models\Financial\Plan::class, function () use ($faker) {

    return [
        'title'                 => Faker::fullName(),
        'description'           => nullable(Faker::sentence()),
        'price'                 => (rand(100, 1000) - 1) * 1000,
        'visited_estate_count'  => rand(1, 100) * 100,
        'registered_estate_count'  => rand(1, 10) * 100,
        'credit_days_count'     => [30, 90, 180, 360][rand(0, 3)],
        'is_active'             => $faker->boolean(80)
    ];
});

$factory->define(App\Models\Financial\Promocode::class, function () use ($faker) {

    return [
        'title'                 => Faker::fullName(),
        'description'           => nullable(Faker::sentence()),
        'code'                  => Str::random(rand(10, 20)),
        'cost'                  => rand(5, 40),
        'quantity'              => nullable(rand(10, 100)),
        'used_count'            => rand(0, 10),
        'expired_at'            => nullable($faker->dateTimeBetween('now', '+3 month')),
        'is_active'             => $faker->boolean(80)
    ];
});
