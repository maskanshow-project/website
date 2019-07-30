<?php

namespace App\GraphQL\Query\User\BlacklistPhoneNumber;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\User\BlacklistPhoneNumberProps;

class BaseBlacklistPhoneNumberQuery extends MainQuery
{
    use BlacklistPhoneNumberProps;

    protected $acceptable = false;

    public function authorize(array $args)
    {
        return $this->checkPermission('read-blacklist_phone_number');
    }
}