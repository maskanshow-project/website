<?php

namespace App\GraphQL\Input;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class RangeInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'range_input',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'min' => [
                'type' => Type::string(),
                'rules' => 'integer'
            ],
            'max' => [
                'type' => Type::string(),
                'rules' => 'integer'
            ]
        ];
    }
}
