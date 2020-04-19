<?php

namespace App\GraphQL\Props\Place;

use App\Models\Places\Country;
use App\ModelFilters\Place\CountryFilter;

trait CountryProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'country';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Country::class;
    
    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = CountryFilter::class;
}