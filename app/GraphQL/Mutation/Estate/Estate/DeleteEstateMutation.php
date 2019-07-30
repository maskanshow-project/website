<?php

namespace App\GraphQL\Mutation\Estate\Estate;

use App\GraphQL\Helpers\DeleteMutation;

class DeleteEstateMutation extends BaseEstateMutation
{
    use DeleteMutation;

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
     * Remove the one or multiple groups from storage.
     *
     * @param  String $features
     * @return Array\JSON
     */
    public function destroy($args)
    {
        $result = false;

        if ( $args['id'] ?? false )
            $result = $this->model::where('id', $args['id'] ?? false);

        elseif ( $args['ids'] ?? false )
            $result = $this->model::whereIn('id', $args['ids'] ?? false);


        $result = $result->when( !auth()->user()->can('delete-estate'), function($query) {
            $query->where('user_id', auth()->id());
        })->delete();
        
        return [
            'status' => $result ? 200 : 400,
            'message' => $result ? 'با موفقیت حذف شد' : 'متاسفانه اطلاعاتی حذف نشد'
        ];
    }
}