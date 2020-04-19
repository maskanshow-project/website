<?php

use Faker\Factory;
use Grimzy\LaravelMysqlSpatial\Types\Point;

$faker = Factory::create('fa_IR');

$factory->define(App\Models\Places\Area::class, function () use($faker) {

    return [
        'city_id'       => 130,
        'name'          => 'منطقه ' . rand(1, 12),
        'coordinates'   => new Point( $faker->latitude, $faker->longitude )
    ];
});

$factory->define(App\Models\Places\Street::class, function () use($faker) {

    return [
        'name'          => $faker->streetName,
        'coordinates'   => new Point( $faker->latitude, $faker->longitude )
    ];
});