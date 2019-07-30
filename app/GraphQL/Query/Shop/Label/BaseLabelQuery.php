<?php

namespace App\GraphQL\Query\Shop\Label;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Estate\LabelProps;

class BaseLabelQuery extends MainQuery
{
    use LabelProps;

    protected $translatable = true;
}