<?php

namespace App\GraphQL\Type\Option;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class CustomerOpinionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'customer_opinion',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'avatar' => [
                'type' => \GraphQL::type('single_media'),
            ],
            'post' => [
                'type' => Type::string(),
            ],
            'fullname' => [
                'type' => Type::string(),
            ],
            'opinion' => [
                'type' => Type::string(),
            ],
        ];
    }
}
