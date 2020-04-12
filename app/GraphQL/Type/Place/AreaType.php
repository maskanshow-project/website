<?php

namespace App\GraphQL\Type\Place;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Places\Area;

class AreaType extends BaseType
{
    protected $attributes = [
        'name' => 'area',
        'description' => 'A type',
        'model' => Area::class
    ];

    public function get_fields()
    {
        return [
            'coordinates' => [
                'type' => \GraphQL::type('coordinate'),
                'is_relation' => false
            ],
            'name' => [
                'type' => Type::string()
            ],
            'city' => [
                'type' => \GraphQL::type('city')
            ],
            'streets' => [
                'type' => Type::listOf(\GraphQL::type('street'))
            ],
            'offices' => [
                'type' => Type::listOf(\GraphQL::type('office')),
                'query' => function ($args, $query) {
                    return $query->inRandomOrder()->limit(5);
                }
            ]
        ];
    }
}
