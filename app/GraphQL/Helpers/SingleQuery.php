<?php

namespace App\GraphQL\Helpers;

use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

trait SingleQuery
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type($this->type);
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull($this->incrementing ? Type::int() : Type::string())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        return $this->getSingleData($args, $getSelectFields());
    }
}
