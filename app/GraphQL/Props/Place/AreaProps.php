<?php

namespace App\GraphQL\Props\Place;

use App\Models\Places\Area;
use App\Http\Requests\v1\Place\AreaRequest;
use App\ModelFilters\Place\AreaFilter;

trait AreaProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'area';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Area::class;
    
    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = AreaFilter::class;
    
    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = AreaRequest::class;
}