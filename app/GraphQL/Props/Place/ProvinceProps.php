<?php

namespace App\GraphQL\Props\Place;

use App\Models\Places\Province;
use App\ModelFilters\Place\ProvinceFilter;

trait ProvinceProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'province';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Province::class;
    
    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = ProvinceFilter::class;
}