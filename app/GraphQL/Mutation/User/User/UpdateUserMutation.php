<?php

namespace App\GraphQL\Mutation\User\User;

use App\GraphQL\Helpers\UpdateMutation;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;

class UpdateUserMutation extends BaseUserMutation
{
    use UpdateMutation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return auth()->id() === $args['id'] || $this->checkPermission("update-user");
    }
}
