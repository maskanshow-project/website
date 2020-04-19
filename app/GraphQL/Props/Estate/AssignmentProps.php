<?php

namespace App\GraphQL\Props\Estate;

use App\Models\Estate\Assignment;
use App\Http\Requests\v1\Estate\AssignmentRequest;
use App\ModelFilters\Estate\AssignmentFilter;

trait AssignmentProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'assignment';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Assignment::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = AssignmentFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = AssignmentRequest::class;
}