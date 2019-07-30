<?php

namespace App\GraphQL\Props\Financial;

use App\Models\Financial\Promocode;
use App\Http\Requests\v1\Financial\PromocodeRequest;
use App\ModelFilters\Financial\PromocodeFilter;

trait PromocodeProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'promocode';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Promocode::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = PromocodeFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = PromocodeRequest::class;
}