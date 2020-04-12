<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Financial\Payment;

class PaymentType extends BaseType
{
    protected $attributes = [
        'name' => 'payment',
        'description' => 'A type',
        'model' => Payment::class
    ];

    public function get_fields()
    {
        return [
            'code' => [
                'type' => Type::string(),
            ],
            'description' => [
                'type' => Type::string(),
            ],
            'amount' => [
                'type' => Type::int(),
            ],
            'ref_id' => [
                'type' => Type::string(),
            ],
            'payment_id' => [
                'type' => Type::string(),
            ],
            'tracking_code' => [
                'type' => Type::string(),
            ],
            'paid_at' => [
                'type' => Type::string(),
            ],
            'expired_at' => [
                'type' => Type::string(),
            ],
            'payer' => [
                'type' => \GraphQL::type('user')
            ],
            'plan' => $this->relationItemField('plan'),
            'promocode' => $this->relationItemField('promocode'),
        ];
    }
}
