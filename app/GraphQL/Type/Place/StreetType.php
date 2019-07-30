<?php

namespace App\GraphQL\Type\Place;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Places\Street;

class StreetType extends BaseType
{
    protected $attributes = [
        'name' => 'StreetType',
        'description' => 'A type',
        'model' => Street::class
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
            'area' => [
                'type' => \GraphQL::type('area')
            ],
        ];
    }
}