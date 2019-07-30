<?php

namespace App\GraphQL\Props\Estate;

use App\Models\Estate\Feature;
use App\Http\Requests\v1\Estate\FeatureRequest;
use App\ModelFilters\Estate\FeatureFilter;

trait FeatureProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'feature';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Feature::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = FeatureFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = FeatureRequest::class;
}