<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Queries;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use SmaaT\EstateBot\Models\CrawledLink;

class CrawledResult extends Query
{
    protected $attributes = [
        'name' => 'crawled_links',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return GraphQL::type('crawled_result');
    }

    public function args(): array
    {
        return [];
    }

    /**
     * Override this in your queries or mutations
     * to provide custom authorization.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return auth()->user()->can('manage-estate-bot');
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return \Cache::remember('estate_bot.crawled_result', 600, function () {
            return [
                'crawled_count' => $crawled_count = CrawledLink::count(),
                'registered_count' => $registered_count = CrawledLink::where('estate_id', '!=', null)->count(),
                'invalid_count' => $crawled_count - $registered_count,
                'crawled_last_week' => CrawledLink::where('crawled_at', '>=', now()->subDay(7))->count(),
                'crawled_today' => CrawledLink::where('crawled_at', '>=', now()->startOfDay())->count(),
                'registered_today' => CrawledLink::where('crawled_at', '>=', now()->startOfDay())->where('estate_id', '!=', null)->count(),
                'registered' => CrawledLink::where('estate_id', '!=', null)->orderBy('crawled_at', 'desc')->take(30)->get(),
                'invalid' => CrawledLink::where('estate_id', null)->orderBy('crawled_at', 'desc')->take(30)->get(),
            ];
        });
    }
}
