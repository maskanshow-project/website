<?php

namespace App\GraphQL\Query\User\Message;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\User\MessageProps;

class BaseMessageQuery extends MainQuery
{
    use MessageProps;

    protected $translatable = true;

    public function authorize(array $args)
    {
        return $this->checkPermission('read-message');
    }
}