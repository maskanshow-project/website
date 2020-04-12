<?php

namespace App\GraphQL\Type\User;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\User\Office;

class OfficeType extends BaseType
{
    protected $incrementing = false;

    protected $attributes = [
        'name' => 'office',
        'description' => 'A type',
        'model' => Office::class
    ];

    public function get_fields()
    {
        return [
            'area' => [
                'type' => \GraphQL::type('area'),
            ],
            'name' => [
                'type' => Type::string()
            ],
            'username' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'address' => [
                'type' => Type::string(),
            ],
            'phone_number' => [
                'type' => Type::string(),
            ],
            'owner' => [
                'type' => \GraphQL::type('user')
            ]
        ];
    }
}
