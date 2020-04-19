<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Queries;

use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use SmaaT\EstateBot\Models\CrawledLink;
use SmaaT\EstateBot\Models\EstateBotProvider;

class BotProviders extends Query
{
    protected $attributes = [
        'name' => 'crawled_links',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('bot_provider'));
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
        return EstateBotProvider::all();
    }
}
