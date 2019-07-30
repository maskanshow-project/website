<?php

namespace App\GraphQL\Props\Place;

use App\Models\Places\Street;
use App\Http\Requests\v1\Place\StreetRequest;
use App\ModelFilters\Place\StreetFilter;

trait StreetProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'street';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Street::class;
    
    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = StreetFilter::class;
    
    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = StreetRequest::class;
}