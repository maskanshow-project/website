<?php

namespace App\GraphQL\Mutation\User\Office;

use App\GraphQL\Helpers\UpdateMutation;

class UpdateOfficeMutation extends BaseOfficeMutation
{
    use UpdateMutation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(array $args)
    {
        return auth()->check();
    }

    /**
     * Find an get a data from Database,
     * or abort 404 not found exception if can't find
     *
     * @param ID $feature
     * @return Model
     */
    public function getModel($data)
    {
        return $this->model::when( auth()->user()->can('update-office'), function($query) use($data) {

            return $query->findOrFail($data);
        }, function($query) use($data) {

            return $query->where('user_id', auth()->id() )->findOrFail($data);
        });
    }
}