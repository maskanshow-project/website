<?php

namespace App\GraphQL\Mutation\Financial\Plan;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Financial\PlanProps;

abstract class BasePlanMutation extends MainMutation
{
    use PlanProps;

    protected $attributes = [
        'name' => 'PlanMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'color' => [
                'type' => Type::string()
            ],
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'price' => [
                'type' => Type::int()
            ],
            'visited_estate_count' => [
                'type' => Type::int()
            ],
            'registered_estate_count' => [
                'type' => Type::int()
            ],
            'credit_days_count' => [
                'type' => Type::int()
            ],
            'is_active' => [
                'type' => Type::boolean()
            ]
        ];
    }
}
