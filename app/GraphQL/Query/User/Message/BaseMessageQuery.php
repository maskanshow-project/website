<?php

namespace App\GraphQL\Query\User\Message;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\User\MessageProps;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;

abstract class BaseMessageQuery extends MainQuery
{
    use MessageProps;

    protected $translatable = true;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('read-message');
    }
}
