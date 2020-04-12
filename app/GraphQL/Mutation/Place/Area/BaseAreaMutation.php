<?php

namespace App\GraphQL\Mutation\Place\Area;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Place\AreaProps;
use Grimzy\LaravelMysqlSpatial\Types\Point;

abstract class BaseAreaMutation extends MainMutation
{
    use AreaProps;

    protected $attributes = [
        'name' => 'AreaMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'city_id' => [
                'type' => Type::int()
            ],
            'name' => [
                'type' => Type::string()
            ],
            'lat' => [
                'type' => Type::float()
            ],
            'lng' => [
                'type' => Type::float()
            ],
        ];
    }

    /**
     * Get the portion of request class
     *
     * @param Request $request
     * @return Array $request
     */
    public function getRequest($request)
    {
        return array_merge($request->all(), [
            'coordinates'   => new Point($request->get('lat'), $request->get('lng'))
        ]);
    }
}
