<?php

namespace App\GraphQL\Mutation\Spec\Spec;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Spec\SpecProps;

abstract class BaseSpecMutation extends MainMutation
{
    use SpecProps;

    protected $attributes = [
        'name' => 'SpecMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'estate_type_id' => [
                'type' => Type::int()
            ],
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'is_active' => [
                'type' => Type::boolean()
            ]
        ];
    }
}
