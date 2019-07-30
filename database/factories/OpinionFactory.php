<?php

use Faker\Factory;
use Morilog\Jalali\Jalalian;

$faker = Factory::create('fa_IR');

$factory->define(App\Models\Opinion\Comment::class, function () use($faker) {
    return [
        'user_id'           => App\User::all()->random()->id,
        'title'             => $faker->text(50),
        'message'           => Faker::sentence(),
        'is_accept'         => $faker->boolean(),
        'jalali_created_at' => Jalalian::forge("now - {$faker->numberBetween(2, 360)} days"),
        // 'created_at'    => $faker->unixTime()
    ];
});