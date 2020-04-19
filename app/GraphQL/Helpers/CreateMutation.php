<?php

namespace App\GraphQL\Helpers;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\SelectFields;

trait CreateMutation
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type($this->type);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission("create-" . ($this->permission_label ? $this->permission_label : $this->type));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(array $args = []): array
    {
        return (new $this->request)->rules($args, 'CREATE');
    }

    public function attributes(): array
    {
        return (new $this->request)->attributes();
    }

    public function args(): array
    {
        return $this->get_args();
    }


    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        return $this->store($args, $getSelectFields());
    }
}
