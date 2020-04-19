<?php

namespace App\GraphQL\Query\Financial\Promocode;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Financial\PromocodeProps;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;

abstract class BasePromocodeQuery extends MainQuery
{
    use PromocodeProps;

    protected $translatable = true;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('read-promocode');
    }
}
