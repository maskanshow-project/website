<?php

namespace App\GraphQL\Props\Financial;

use App\Models\Financial\Payment;
use App\Http\Requests\v1\Financial\PaymentRequest;
use App\ModelFilters\Financial\PaymentFilter;

trait PaymentProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'payment';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Payment::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = PaymentFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = PaymentRequest::class;
}