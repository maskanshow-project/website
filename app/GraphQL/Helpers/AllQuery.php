<?php

namespace App\GraphQL\Helpers;

use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

trait AllQuery
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::paginate($this->type);
    }

    public function args(): array
    {
        return array_merge($this->has_more_args ?? false ? $this->get_args() : [], [
            'per_page' => [
                'type' => Type::int()
            ],
            'page' => [
                'type' => Type::int()
            ],
            'query' => [
                'type' => Type::string()
            ],
            'ordering' => [
                'type' => Type::string()
            ],
            'ids' => [
                'type' => Type::listOf($this->incrementing ? Type::int() : Type::string())
            ]
        ]);
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        return $this->getAllData($args, $getSelectFields());
    }
}
