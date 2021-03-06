<?php

namespace App\GraphQL\Type\User;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\BlacklistPhoneNumber;

class BlacklistPhoneNumberType extends BaseType
{
    protected $incrementing = false;

    protected $attributes = [
        'name' => 'blacklist_phone_number',
        'description' => 'A type',
        'model' => BlacklistPhoneNumber::class
    ];

    public function get_fields()
    {
        return [
            'phone_number' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
        ];
    }
}
