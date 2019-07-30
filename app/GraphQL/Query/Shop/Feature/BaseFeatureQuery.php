<?php

namespace App\GraphQL\Query\Shop\Feature;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Estate\FeatureProps;

class BaseFeatureQuery extends MainQuery
{
    use FeatureProps;

    protected $translatable = true;
}