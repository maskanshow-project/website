<?php

namespace App\GraphQL\Props\Estate;

use App\Models\Estate\Label;
use App\Http\Requests\v1\Estate\LabelRequest;
use App\ModelFilters\Estate\LabelFilter;

trait LabelProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'label';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Label::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = LabelFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = LabelRequest::class;
}