<?php

namespace App\GraphQL\Props\Financial;

use App\Models\Financial\Plan;
use App\Http\Requests\v1\Financial\PlanRequest;
use App\ModelFilters\Financial\PlanFilter;

trait PlanProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'plan';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Plan::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = PlanFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = PlanRequest::class;
}