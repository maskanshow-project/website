<?php

namespace App\GraphQL\Mutation\User\User;

use App\GraphQL\Helpers\ActiveMutation;

class ActiveUserMutation extends BaseUserMutation
{
    use ActiveMutation;

    protected $acceptable_field = 'can_has_member';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(array $args)
    {
        return $this->checkPermission('active-user');
    }
}