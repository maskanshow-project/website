<?php

namespace App\GraphQL\Mutation\User\Message;

use App\GraphQL\Helpers\DeleteMutation;

class DeleteMessageMutation extends BaseMessageMutation
{
    use DeleteMutation;
}