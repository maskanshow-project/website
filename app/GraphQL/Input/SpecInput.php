<?php

namespace App\GraphQL\Input;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class SpecInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'spec_input',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::int()
            ],
            'is_required' => [
                'type' => Type::boolean()
            ],
            'value' => [
                'type' => Type::int()
            ],
            'values' => [
                'type' => Type::listOf(Type::int())
            ],
            'data' => [
                'type' => Type::string()
            ],
        ];
    }
}
