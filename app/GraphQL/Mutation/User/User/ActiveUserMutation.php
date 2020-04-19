<?php

namespace App\GraphQL\Mutation\User\User;

use App\GraphQL\Helpers\ActiveMutation;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;

class ActiveUserMutation extends BaseUserMutation
{
    use ActiveMutation;

    protected $acceptable_field = 'can_has_member';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission('active-user');
    }
}
