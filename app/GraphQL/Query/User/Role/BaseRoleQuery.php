<?php

namespace App\GraphQL\Query\User\Role;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\User\RoleProps;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;

abstract class BaseRoleQuery extends MainQuery
{
    use RoleProps;

    protected $translatable = true;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('read-role');
    }
}
