<?php

namespace App\GraphQL\Query\User\Favorite;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Query\MainQuery;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;

class FavoriteQuery extends MainQuery
{
    public function type()
    {
        return \GraphQL::paginate('estate');
    }

    public function args()
    {
        return [
            'query' => [
                'type' => Type::string()
            ]
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        return auth()->user()->favorites()
            ->where('is_active', 1)
            ->search( $args['query'] ?? null )
            ->with( $fields->getRelations() )
            ->select( $this->getSelectFields($fields) )
            ->paginate(10);
    }
}