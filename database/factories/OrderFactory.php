<?php

use Faker\Factory;
use Morilog\Jalali\Jalalian;
use App\Models\Financial\ShippingMethod;
use App\Models\Financial\OrderStatus;

$faker = Factory::create('fa_IR');

$factory->define(App\Models\Financial\Transaction::class, function () use($faker) {
    return [
        'value'         => $faker->numberBetween(0, 5000000),
        'description'   => [ null, Faker::sentence ][ $faker->boolean() ],
        'type'          => [ $faker->boolean() ],
        'docs'          => [ null, function () use ( $faker ) {
            $docs = [];
            for ($i = 0; $i < rand(0, 5); ++$i)
                $docs[] = [ 'title' => $faker->company, 'file' => $faker->words().$faker->fileExtension ];
            return $docs;
        }][ $faker->boolean() ],
    ];
});