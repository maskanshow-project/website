<?php

namespace App\GraphQL\Props\User;

use App\Models\BlacklistPhoneNumber;
use App\Http\Requests\User\v1\BlacklistPhoneNumberRequest;

trait BlacklistPhoneNumberProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'blacklist_phone_number';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = BlacklistPhoneNumber::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = BlacklistPhoneNumberRequest::class;
}