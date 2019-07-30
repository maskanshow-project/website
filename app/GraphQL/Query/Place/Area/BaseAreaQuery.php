<?php

namespace App\GraphQL\Query\Place\Area;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Place\AreaProps;

class BaseAreaQuery extends MainQuery
{
    use AreaProps;

    protected $acceptable = false;
}