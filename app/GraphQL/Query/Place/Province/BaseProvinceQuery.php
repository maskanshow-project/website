<?php

namespace App\GraphQL\Query\Place\Province;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Place\ProvinceProps;

abstract class BaseProvinceQuery extends MainQuery
{
    use ProvinceProps;

    protected $acceptable = false;
}
