<?php

namespace App\GraphQL\Helpers;

use Closure;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;
use Illuminate\Support\Str;

trait ActiveMutation
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
        $active_type = Str::replaceFirst('is_', '', $this->acceptable_field);

        return $this->checkPermission("{$active_type}-" . ($this->permission_label ? $this->permission_label : $this->type));
    }

    public function args(): array
    {
        return [
            'id'        => [
                'type' => $this->incrementing ? Type::int() : Type::string()
            ],
            'ids'       => [
                'type' => Type::listOf($this->incrementing ? Type::int() : Type::string())
            ],
            'status'    => [
                'type' => Type::nonNull(Type::boolean()),
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        return $this->active($args, $getSelectFields());
    }
}
