<?php

namespace App\GraphQL\Mutation\User\BlacklistPhoneNumber;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\User\BlacklistPhoneNumberProps;

class BaseBlacklistPhoneNumberMutation extends MainMutation
{
    use BlacklistPhoneNumberProps;
    
    protected $attributes = [
        'name' => 'BlacklistPhoneNumberMutation',
        'description' => 'A mutation'
    ];

    public function getArgs()
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