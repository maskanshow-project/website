<?php

namespace App\GraphQL\Mutation\User\Role;

use App\GraphQL\Helpers\DeleteMutation;

class DeleteRoleMutation extends BaseRoleMutation
{
    use DeleteMutation;

    /**
     * Remove the one or multiple groups from storage.
     *
     * @param  String $features
     * @return Array\JSON
     */
    public function destroy($args)
    {
        $result = $this->model::whereNotIn('name', ['owner', 'consultant']);

        if ( $args['id'] ?? false )
            $result = $result->where('id', $args['id'] ?? false)->delete();

        elseif ( $args['ids'] ?? false )
            $result = $result->whereIn('id', $args['ids'] ?? false)->delete();
        
        return [
            'status' => $result ? 200 : 400,
            'message' => $result ? 'با موفقیت حذف شد' : 'متاسفانه اطلاعاتی حذف نشد'
        ];
    }
}