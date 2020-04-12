<?php

namespace App\GraphQL\Query\User\Office;

use App\GraphQL\Helpers\SingleQuery;
use GraphQL\Type\Definition\Type;

class OfficeQuery extends BaseOfficeQuery
{
    use SingleQuery;

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::int(),
                'rules' => 'required_without:username'
            ],
            'username' => [
                'type' => Type::string(),
                'rules' => 'required_without:id'
            ],
        ];
    }

    /**
     * Find an get a data from Database,
     * or abort 404 not found exception if can't find
     *
     * @param ID $feature
     * @return Model
     */
    public function getSingleData($args, $fields)
    {
        $data = $this->model::select($this->getSelectFields($fields))
            ->with($fields->getRelations());


        $this->showOnlyAtiveData($data);


        if ($args['id'] ?? false)
            return $data->findOrFail($args['id']);

        elseif ($args['username'] ?? false)
            return $data->whereUsername($args['username'])->firstOrFail();
    }
}
