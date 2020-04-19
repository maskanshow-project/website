<?php

namespace App\GraphQL\Props\Estate;

use App\Models\Estate\Estate;
use App\ModelFilters\Estate\EstateFilter;
use App\Http\Requests\v1\Estate\EstateRequest;

trait EstateProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'estate';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Estate::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = EstateFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = EstateRequest::class;
}