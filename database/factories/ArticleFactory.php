<?php

use Faker\Factory;
use Morilog\Jalali\Jalalian;
use App\Models\Blog\Article;

$faker = Factory::create('fa_IR');

function nullable ( $value )
{
    $faker = Factory::create('fa_IR');
    
    return [ null, $value ][ $faker->boolean(80) ];
}

function image ($file)
{
    return [
        'big'       => $file,
        'medium'    => $file,
        'small'     => $file,
        'tiny'      => $file,
    ];
}


$factory->define(Article::class, function () use ($faker) {
    return [
        'title'             => $faker->name(),
        'description'       => nullable( Faker::sentence(250) ),
        'body'              => Faker::paragraph(),
        'reading_time'      => rand(1, 50),
        'jalali_created_at' => Jalalian::forge("now - {$faker->numberBetween(2, 360)} days"),
        'is_active'         => $faker->boolean(80)
    ];
});
