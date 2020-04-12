<?php

namespace App\GraphQL\Mutation\Estate\Estate;

use App\GraphQL\Helpers\ActiveMutation;
use GraphQL\Type\Definition\Type;

class ActiveEstateMutation extends BaseEstateMutation
{
    use ActiveMutation;

    public function args(): array
    {
        return [
            'id'        => [
                'type' => $this->incrementing ? Type::int() : Type::string()
            ],
            'ids'       => [
                'type' => Type::listOf($this->incrementing ? Type::int() : Type::string())
            ],
            'reason' => [
                'type' => Type::string(),
                'rules' => 'required_if:status,false'
            ],
            'status'    => [
                'type' => Type::boolean(),
            ],
        ];
    }

    /**
     * Remove the one or multiple groups from storage.
     *
     * @param  String $features
     * @return Array\JSON
     */
    public function active($args)
    {
        $result = false;

        if ($args['id'] ?? false)
            $model = $this->model::where('id', $args['id'] ?? false);

        elseif ($args['ids'] ?? false)
            $model = $this->model::whereIn('id', $args['ids'] ?? false);


        if ($model ?? false) {
            $result = $model->update([
                $this->acceptable_field => $args['status'] ?? true,
                'reject_reason' => $args['status'] ?? true ? null : $args['reason'] ?? null
            ]);
        }

        return [
            'count'     => $result ? $result : 0,
            'status'    => $result ? 200 : 400,
            'message'   => $result
                ? 'با موفقیت ' . ($args['status'] ?? true ? 'تایید' : 'رد') . ' ' . ($result === 1 ? 'شد' : 'شدند')
                : 'متاسفانه اطلاعاتی حذف نشد'
        ];
    }
}
