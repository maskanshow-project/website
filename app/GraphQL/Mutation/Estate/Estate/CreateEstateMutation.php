<?php

namespace App\GraphQL\Mutation\Estate\Estate;

use App\GraphQL\Helpers\CreateMutation;

class CreateEstateMutation extends BaseEstateMutation
{
    use CreateMutation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(array $args)
    {
        return auth()->check();
    }
}