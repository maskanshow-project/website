<?php

namespace App\GraphQL\Query\Place\City;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Place\CityProps;

abstract class BaseCityQuery extends MainQuery
{
    use CityProps;

    protected $acceptable = false;
}
