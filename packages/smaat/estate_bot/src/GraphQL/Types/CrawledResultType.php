<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Types;

use GraphQL;
use Cache;
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
                'type' => Type::int(),
            ],
            'registered_count' => [
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
                'type' => Type::listOf(GraphQL::type('crawled_link')),
                'args' => [
                    'page' => [
                        'type' => Type::int()
                    ],
                    'query' => [
                        'type' => Type::string()
                    ]
                ]
            ],
            'invalid' => [
                'type' => Type::listOf(GraphQL::type('crawled_link')),
                'args' => [
                    'page' => [
                        'type' => Type::int()
                    ],
                    'query' => [
                        'type' => Type::string()
                    ]
                ]
            ],
        ];
    }

    protected function resolveCrawledCountField($root, $args)
    {
        return Cache::remember('estate_bot.crawled_result.crawled_count', 600, function () {
            return CrawledLink::count();
        });
    }

    protected function resolveRegisteredCountField($root, $args)
    {
        return Cache::remember('estate_bot.crawled_result.registered_count', 600, function () {
            return CrawledLink::where('estate_id', '!=', null)->count();
        });
    }

    protected function resolveCrawledLastWeekField($root, $args)
    {
        return Cache::remember('estate_bot.crawled_result.crawled_last_week', 600, function () {
            return CrawledLink::where('crawled_at', '>=', now()->subDay(7))->count();
        });
    }

    protected function resolveCrawledTodayField($root, $args)
    {
        return Cache::remember('estate_bot.crawled_result.crawled_today', 600, function () {
            return CrawledLink::where('crawled_at', '>=', now()->startOfDay())->count();
        });
    }

    protected function resolveRegisteredTodayField($root, $args)
    {
        return Cache::remember('estate_bot.crawled_result.registered_today', 600, function () {
            return CrawledLink::where('crawled_at', '>=', now()->startOfDay())->where('estate_id', '!=', null)->count();
        });
    }

    protected function resolveRegisteredField($root, $args)
    {
        return CrawledLink::where('estate_id', '!=', null)
            ->orderBy('crawled_at', 'desc')
            ->search($args['query'] ?? null)
            ->paginate(
                20,
                ['*'],
                'page',
                $args['page'] ?? 1
            );
    }

    protected function resolveInvalidField($root, $args)
    {
        return CrawledLink::where('estate_id', null)
            ->orderBy('crawled_at', 'desc')
            ->search($args['query'] ?? null)
            ->paginate(
                20,
                ['*'],
                'page',
                $args['page'] ?? 1
            );
    }
}
