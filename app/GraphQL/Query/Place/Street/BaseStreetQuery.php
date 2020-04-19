<?php

namespace App\GraphQL\Query\Place\Street;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Place\StreetProps;

abstract class BaseStreetQuery extends MainQuery
{
    use StreetProps;

    protected $acceptable = false;
}
