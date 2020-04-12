<?php

namespace App\GraphQL\Mutation\User\Message;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\User\MessageProps;

abstract class BaseMessageMutation extends MainMutation
{
    use MessageProps;

    protected $attributes = [
        'name' => 'MessageMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'title' => [
                'type' => Type::string()
            ],
            'message' => [
                'type' => Type::string()
            ],
            'type' => [
                'type' => Type::string()
            ],
            'expired_at' => [
                'type' => Type::string()
            ],
            'role_id' => [
                'type' => Type::int()
            ],
            'is_active' => [
                'type' => Type::boolean()
            ]
        ];
    }
}
