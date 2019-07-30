<?php

namespace App\GraphQL\Query\Shop\EstateType;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Estate\EstateTypeProps;

class BaseEstateTypeQuery extends MainQuery
{
    use EstateTypeProps;

    protected $translatable = true;
}