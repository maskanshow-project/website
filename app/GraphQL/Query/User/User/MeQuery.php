<?php

namespace App\GraphQL\Query\User\User;

use Closure;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class MeQuery extends BaseUserQuery
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('me');
    }

    public function args(): array
    {
        return [
            'page' => [
                'type' => Type::int()
            ]
        ];
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        return $this->getSingleData(['id' => auth()->user()->id], $getSelectFields());
    }
}
