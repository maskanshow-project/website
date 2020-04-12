<?php

namespace App\GraphQL\Query\Shop\Spec;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Spec\SpecProps;
use Closure;

abstract class BaseSpecQuery extends MainQuery
{
    use SpecProps;

    protected $acceptable = false;

    protected $translatable = true;

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('read-spec');
    }
}
