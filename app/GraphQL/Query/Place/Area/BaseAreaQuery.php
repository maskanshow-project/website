<?php

namespace App\GraphQL\Query\Place\Area;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Place\AreaProps;

class BaseAreaQuery extends MainQuery
{
    use AreaProps;

    protected $acceptable = false;

    /**
     * Get the dynamik per_page property of the models
     *
     * @param int|10 $max
     * @param int|100 $min
     * @return void
     */
    public function getPerPage($args, int $max = 100, int $min = 1)
    {
        return 30;
    }
}
