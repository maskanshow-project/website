<?php

namespace App\GraphQL\Query\Shop\Assignment;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Estate\AssignmentProps;

class BaseAssignmentQuery extends MainQuery
{
    use AssignmentProps;

    protected $translatable = true;
}