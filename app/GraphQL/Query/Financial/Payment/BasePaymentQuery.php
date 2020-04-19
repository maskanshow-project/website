<?php

namespace App\GraphQL\Query\Financial\Payment;

use App\GraphQL\Query\MainQuery;
use App\GraphQL\Props\Financial\PaymentProps;

abstract class BasePaymentQuery extends MainQuery
{
    use PaymentProps;

    protected $acceptable = false;

    public function applyFilters($args, $data)
    {
        if (!$this->checkPermission('read-payment'))
            $data->where('user_id', auth()->id());
    }
}
