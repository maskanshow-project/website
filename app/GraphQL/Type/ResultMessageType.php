<?php

namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class ResultMessageType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ResultMessageType',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'code' => [
                'type' => Type::string()
            ],
            'count' => [
                'type' => Type::int()
            ],
            'status' => [
                'type' => Type::int()
            ],
            'message' => [
                'type' => Type::string()
            ]
        ];
    }
}