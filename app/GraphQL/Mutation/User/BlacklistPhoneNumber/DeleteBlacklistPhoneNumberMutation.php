<?php

namespace App\GraphQL\Mutation\User\BlacklistPhoneNumber;

use App\GraphQL\Helpers\DeleteMutation;

class DeleteBlacklistPhoneNumberMutation extends BaseBlacklistPhoneNumberMutation
{
    use DeleteMutation;
}