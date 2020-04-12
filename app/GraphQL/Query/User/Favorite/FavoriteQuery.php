<?php

namespace App\GraphQL\Query\User\Favorite;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Query\MainQuery;
use Closure;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;

class FavoriteQuery extends MainQuery
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::paginate('estate');
    }

    public function args(): array
    {
        return [
            'query' => [
                'type' => Type::string()
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $fields = $getSelectFields();

        return auth()->user()->favorites()
            ->where('is_active', 1)
            ->search($args['query'] ?? null)
            ->with($fields->getRelations())
            ->select($this->getSelectFields($fields))
            ->paginate(10);
    }
}
