<?php

use Faker\Factory;
use Morilog\Jalali\Jalalian;

$faker = Factory::create('fa_IR');

$factory->define(App\Models\Group\Subject::class, function () use($faker) {

    return [
        'user_id'           => App\User::all()->random()->id,
        'title'             => Faker::fullName(),
        'description'       => nullable( Faker::sentence() ),
        'jalali_created_at' => Jalalian::forge("now - {$faker->numberBetween(2, 360)} days"),
        'is_active'         => $faker->boolean(80)        
    ];
});