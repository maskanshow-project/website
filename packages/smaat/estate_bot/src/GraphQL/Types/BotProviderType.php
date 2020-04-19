<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use SmaaT\EstateBot\Models\EstateBotProvider;

class BotProviderType extends GraphQLType
{
    protected $attributes = [
        'name' => 'bot_provider',
        'description' => 'A type',
        'model' => EstateBotProvider::class
    ];

    public function fields(): array
    {
        return [
            'id'     => [
                'type' => Type::int()
            ],
            'provider'      => [
                'type' => Type::string()
            ],
            'username'      => [
                'type' => Type::string()
            ],
            'password'        => [
                'type' => Type::string()
            ],
            'base_url'        => [
                'type' => Type::string()
            ],
        ];
    }
}
