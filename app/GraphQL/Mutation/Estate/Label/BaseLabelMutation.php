<?php

namespace App\GraphQL\Mutation\Estate\Label;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Estate\LabelProps;

abstract class BaseLabelMutation extends MainMutation
{
    use LabelProps;

    protected $attributes = [
        'name' => 'LabelMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'title' => [
                'type' => Type::string()
            ],
            'color' => [
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
