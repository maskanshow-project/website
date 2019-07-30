<?php

namespace App\GraphQL\Props\User;

use App\Models\User\Message;
use App\ModelFilters\User\MessageFilter;
use App\Http\Requests\User\v1\MessageRequest;

trait MessageProps
{
    /**
     * Type of this controller for use in messages
     *
     * @var string
     */
    protected $type = 'message';

    /**
     * The model of this controller
     *
     * @var Model
     */
    protected $model = Message::class;

    /**
     * Filter class of this eloquent model
     *
     * @var ModelFilter
     */
    protected $filter = MessageFilter::class;

    /**
     * The request class of model for validation and authorization
     *
     * @var Request
     */
    protected $request = MessageRequest::class;
}