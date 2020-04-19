<?php

namespace App\GraphQL\Props\User;

use App\Models\User\Office;
use App\ModelFilters\User\OfficeFilter;
use App\Http\Requests\v1\User\OfficeRequest;

trait OfficeProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'office';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Office::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = OfficeFilter::class;
    
    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = OfficeRequest::class;
}