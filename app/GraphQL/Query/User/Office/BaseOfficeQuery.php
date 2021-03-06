<?php

namespace App\GraphQL\Query\User\Office;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\User\OfficeProps;

abstract class BaseOfficeQuery extends MainQuery
{
    use OfficeProps;

    protected $acceptable = false;
}
