<?php

namespace App\GraphQL\Query\User\BlacklistPhoneNumber;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\User\BlacklistPhoneNumberProps;
use Closure;

abstract class BaseBlacklistPhoneNumberQuery extends MainQuery
{
    use BlacklistPhoneNumberProps;

    protected $acceptable = false;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('read-blacklist_phone_number');
    }
}
