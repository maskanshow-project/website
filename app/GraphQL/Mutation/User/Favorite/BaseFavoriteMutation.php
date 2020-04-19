<?php

namespace App\GraphQL\Mutation\User\Favorite;

use App\GraphQL\Mutation\MainMutation;
use GraphQL\Type\Definition\Type;

abstract class BaseFavoriteMutation extends MainMutation
{
    protected $attributes = [
        'name' => 'FavoriteMutation',
        'description' => 'A mutation'
    ];

    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('result');
    }

    public function args(): array
    {
        return [
            'estate' => [
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }
}
