<?php

namespace App\GraphQL\Query\Shop\Feature;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Estate\FeatureProps;

abstract class BaseFeatureQuery extends MainQuery
{
    use FeatureProps;

    protected $translatable = true;
}
