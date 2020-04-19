<?php

namespace App\GraphQL\Mutation\User\BlacklistPhoneNumber;

use App\GraphQL\Helpers\UpdateMutation;

class UpdateBlacklistPhoneNumberMutation extends BaseBlacklistPhoneNumberMutation
{
    use UpdateMutation;
}