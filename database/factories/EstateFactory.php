<?php

use Faker\Factory;
use Morilog\Jalali\Jalalian;
use App\Models\Feature\Brand;
use App\Models\Feature\Unit;
use App\Models\Feature\Color;
use App\Models\Feature\Size;
use App\Models\Feature\Warranty;
use App\Models\Estate\Assignment;
use App\Models\Estate\EstateType;
use App\Models\Estate\Label;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\Role;

$faker = Factory::create('fa_IR');


$factory->define(App\Models\Estate\Estate::class, function () use($faker) {
    
    $title = ['ملک لاکچری', 'خانه استخر دار', 'ملک فول آپشن', 'ملک نقلی', 'ملک بازسازی شده'][rand(0, 4)];
    $title .= ' ' . ['حاشیه', 'جنب', 'داخل', 'نزدیک'][rand(0,3)];
    $title .= ' ' . $faker->streetName;

    return [
        'assignment_id'         => Assignment::all()->random()->id ?? null,
        'estate_type_id'        => EstateType::all()->random()->id ?? null,
        'label_id'              => nullable( Label::all()->random()->id ?? null ),
        'role_id'               => nullable( Role::all()->random()->id ?? null ),
        'code'                  => $faker->ean8,
        'title'                 => nullable( $title ),
        'description'           => Faker::sentence(),
        'sales_price'           => $faker->numberBetween(60000000, 10000000000),
        'mortgage_price'        => $faker->numberBetween(5000000, 200000000),
        'rental_price'          => $faker->numberBetween(200000, 10000000),
        'area'                  => $faker->numberBetween(40, 400),
        'aparat_video'          => 'SEQ2V',
        'address'               => $faker->streetAddress,
        'plaque'                => rand(1, 200),
        'landlord_fullname'     => $faker->name(),
        'landlord_phone_number' => $faker->phoneNumber,
        'coordinates'           => new Point( $faker->latitude(35.5, 36.5), $faker->longitude(null, 58.5, 59.5) ),
        'advantages'            => $faker->sentences( rand(1, 10) ),
        'disadvantages'         => $faker->sentences( rand(1, 10) ),
        'views_count'           => $faker->numberBetween(0, 10000),
        'jalali_created_at'     => Jalalian::forge("now - {$faker->numberBetween(2, 360)} days"),
        'is_active'             => $faker->boolean(80)
    ];
});

$factory->define(App\Models\Estate\Label::class, function () use($faker) {

    $labels = [
        'توقف تولید',
        'به زودی',
        'عدم فروش',
        'عدم موجودی',
        'تخفیف ویژه',
        'از رده خارج'
    ];

    return [
        'title'             => $labels[ rand(0, count($labels) - 1) ],
        'description'       => Faker::sentence(),
        'color'             => $faker->hexColor,
        'is_active'         => $faker->boolean(80)
    ];
});