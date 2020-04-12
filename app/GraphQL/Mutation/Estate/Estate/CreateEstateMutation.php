<?php

namespace App\GraphQL\Mutation\Estate\Estate;

use App\GraphQL\Helpers\CreateMutation;
use Closure;

class CreateEstateMutation extends BaseEstateMutation
{
    use CreateMutation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return auth()->check();
    }
}
