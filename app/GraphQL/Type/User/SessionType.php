<?php

namespace App\GraphQL\Type\User;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\User\ActiveSession;

class SessionType extends BaseType
{
    protected $incrementing = false;

    protected $attributes = [
        'name' => 'session',
        'description' => 'A type',
        'model' => ActiveSession::class
    ];

    public function get_fields()
    {
        return [
            'user_agent' => [
                'type' => Type::string()
            ],
        ];
    }
}
