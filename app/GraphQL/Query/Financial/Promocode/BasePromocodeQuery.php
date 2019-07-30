<?php

namespace App\GraphQL\Query\Financial\Promocode;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Financial\PromocodeProps;

class BasePromocodeQuery extends MainQuery
{
    use PromocodeProps;
    
    protected $translatable = true;

    public function authorize(array $args)
    {
        return $this->checkPermission('read-promocode');
    }
}