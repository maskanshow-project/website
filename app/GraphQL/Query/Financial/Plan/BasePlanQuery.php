<?php

namespace App\GraphQL\Query\Financial\Plan;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Financial\PlanProps;

class BasePlanQuery extends MainQuery
{
    use PlanProps;
    
    protected $translatable = true;
}