<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use SmaaT\EstateBot\Models\CrawledLink;

class CrawledResultType extends GraphQLType
{
    protected $attributes = [
        'name' => 'crawled_result',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'crawled_count' => [
                'type' => Type::int()
            ],
            'registered_count' => [
                'type' => Type::int()
            ],
            'invalid_count' => [
                'type' => Type::int()
            ],
            'crawled_last_week' => [
                'type' => Type::int()
            ],
            'crawled_today' => [
                'type' => Type::int()
            ],
            'registered_today'  => [
                'type' => Type::int()
            ],
            'registered' => [
                'type' => Type::listOf(GraphQL::type('crawled_link'))
            ],
            'invalid' => [
                'type' => Type::listOf(GraphQL::type('crawled_link'))
            ],
        ];
    }
}
