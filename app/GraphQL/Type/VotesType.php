<?php

namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use OwenIt\Auditing\Models\Audit;

class VotesType extends GraphQLType
{
    protected $attributes = [
        'name' => 'votes',
        'description' => 'A type',
    ];

    public function fields(): array
    {
        return [
            'likes' => [
                'type' => Type::int()
            ],
            'dislikes' => [
                'type' => Type::int()
            ]
        ];
    }
}
