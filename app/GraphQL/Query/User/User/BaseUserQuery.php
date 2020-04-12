<?php

namespace App\GraphQL\Query\User\User;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\User\UserProps;
use Closure;

abstract class BaseUserQuery extends MainQuery
{
    use UserProps;

    protected $incrementing = false;

    protected $acceptable = false;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('read-user');
    }
}
