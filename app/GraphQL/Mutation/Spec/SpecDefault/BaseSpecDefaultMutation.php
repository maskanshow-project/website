<?php

namespace App\GraphQL\Mutation\Spec\SpecDefault;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Spec\SpecDefaultProps;

abstract class BaseSpecDefaultMutation extends MainMutation
{
    use SpecDefaultProps;

    protected $permission_label = 'spec';

    protected $attributes = [
        'name' => 'SpecDefaultMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'spec_row_id' => [
                'type' => Type::int()
            ],
            'value' => [
                'type' => Type::string()
            ],
            'similar_titles' => [
                'type' => Type::listOf(Type::string()),
                'selectable' => false
            ],
        ];
    }
}
