<?php

namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class TagType extends GraphQLType
{
    protected $attributes = [
        'name' => 'tag',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'name' => [
                'type' => Type::string()
            ],
        ];
    }
}
