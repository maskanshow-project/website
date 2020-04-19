<?php

namespace App\GraphQL\Props\Estate;

use App\Models\Estate\EstateType;
use App\Http\Requests\v1\Estate\EstateTypeRequest;
use App\ModelFilters\Estate\EstateTypeFilter;

trait EstateTypeProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'estate_type';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = EstateType::class;
    
    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = EstateTypeFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = EstateTypeRequest::class;
}