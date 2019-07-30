<?php


use Faker\Factory;

$faker = Factory::create('fa_IR');

use Ybazli\Faker\Facades\Faker;
use Morilog\Jalali\Jalalian;
use App\Models\BlacklistPhoneNumber;

$avatars = [
    [
        'img' => '/tests/avatars/man_1.png'  
    ], [
        'img' => '/tests/avatars/man_2.jpg'
    ], [
        'img' => '/tests/avatars/women_1.png'
    ], [
        'img' => '/tests/avatars/women_2.jpg'
    ]
];

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function () use($faker , $avatars) {
    
    $select = rand( 0, count($avatars)-1 );

    return [
        'first_name'            => nullable( $faker->firstName() ),
        'last_name'             => nullable( $faker->lastName() ),
        'username'              => $faker->userName,
        'email'                 => $faker->unique()->safeEmail,
        'email_verified_at'     => now(),
        'password'              => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'address'               => nullable( $faker->address ),
        'phone_number'          => nullable( $faker->phoneNumber ),
        'national_code'         => nullable( '0'.$faker->numberBetween(910000000, 959999999) ),
        'gender'                => nullable( $faker->boolean() ),
        'remember_token'        => str_random(10),
        'visited_estate_count'  => rand(0, 1000),
        'remaining_hits_count'  => rand(0, 1000),
        'registered_estate_count' => rand(1, 1000),
        'remaining_registered_count' => rand(1, 100),
        'validity_end_at'       => nullable( $faker->dateTimeBetween('now', '+1 years') ),
        'payments_count'        => rand(0, 20),
        'total_payments'        => rand(0, 5000) * 1000,
        'last_payment'          => nullable( $faker->dateTimeBetween('-3 years', 'now') ),
        'jalali_created_at'     => Jalalian::forge("now - {$faker->numberBetween(2, 360)} days")
    ];
});

$factory->define(BlacklistPhoneNumber::class, function () use($faker , $avatars) {
    return [
        'phone_number'      => $faker->phoneNumber,
        'description'       => nullable( $faker->sentence() ),
    ];
});