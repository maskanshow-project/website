<?php

namespace App\GraphQL\Type\Option;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class UserSettingType extends GraphQLType
{
    protected $attributes = [
        'name' => 'user_settings',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [];
    }
}
