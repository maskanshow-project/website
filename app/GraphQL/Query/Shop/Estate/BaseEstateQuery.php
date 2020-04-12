<?php

namespace App\GraphQL\Query\Shop\Estate;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Estate\EstateProps;

abstract class BaseEstateQuery extends MainQuery
{
    use EstateProps;

    protected $incrementing = false;

    protected $translatable = true;

    public function showOnlyAtiveData($data)
    {
        if (!$this->acceptable)
            return;

        if (!$this->checkPermission("read-{$this->type}"))
            $data->where(function ($query) {
                $query->where($this->acceptable_field, 1)->orWhere('user_id', auth()->id());
            });
    }
}
