<?php

namespace App\GraphQL\Query\Place\Country;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Place\CountryProps;

abstract class BaseCountryQuery extends MainQuery
{
    use CountryProps;

    protected $acceptable = false;
}
