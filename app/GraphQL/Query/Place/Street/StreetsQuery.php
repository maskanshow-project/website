<?php

namespace App\GraphQL\Query\Place\Street;

use App\GraphQL\Helpers\AllQuery;
use GraphQL\Type\Definition\Type;

class StreetsQuery extends BaseStreetQuery
{
    use AllQuery;

    protected $has_more_args = true;

    public function get_args()
    {
        return [
            'area' => [
                'type' => Type::int()
            ],
        ];
    }

    public function getPortionOfData($data, $args)
    {
        return $data->when( $args['area'] ?? false , function($query, $area) {
                $query->where('area_id', $area);
            })
            ->paginate(
                isset($args['per_page']) ? $this->getPerPage($args) : 10
                ,
                ['*'],
                'page',
                $args['page'] ?? 1
            );
    }
}