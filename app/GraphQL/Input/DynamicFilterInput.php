<?php

namespace App\GraphQL\Input;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class DynamicFilterInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'DynamicFilterInput',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'row_id' => [
                'type' => Type::int(),
                'rules' => 'required'
            ],
            'data' => [
                'type' => Type::string(),
                'rules' => 'nullable'
            ],
            'values' => [
                'type' => Type::listOf( Type::int() ),
                'rules' => 'nullable'
            ]
        ];
    }
}