<?php

namespace App\GraphQL\Mutation\Estate\Assignment;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Estate\AssignmentProps;

abstract class BaseAssignmentMutation extends MainMutation
{
    use AssignmentProps;

    protected $attributes = [
        'name' => 'AssignmentMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'color' => [
                'type' => Type::string()
            ],
            'has_sales_price' => [
                'type' => Type::boolean()
            ],
            'has_mortgage_price' => [
                'type' => Type::boolean()
            ],
            'has_rental_price' => [
                'type' => Type::boolean()
            ],
            'is_active' => [
                'type' => Type::boolean()
            ]
        ];
    }
}
