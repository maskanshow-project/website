<?php

namespace App\GraphQL\Helpers;

use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

trait DeleteMutation
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('result');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission("delete-" . ($this->permission_label ? $this->permission_label : $this->type));
    }

    public function args(): array
    {
        return [
            'id'    => [
                'type' => $this->incrementing ? Type::int() : Type::string(),
                'rules' => 'required_without:ids'
            ],
            'ids'    => [
                'type' => Type::listOf($this->incrementing ? Type::int() : Type::string()),
                'rules' => 'required_without:id'
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        return $this->destroy($args, $getSelectFields());
    }
}
