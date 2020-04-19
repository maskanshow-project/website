<?php

namespace App\GraphQL\Mutation\User\Office;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\User\OfficeProps;

abstract class BaseOfficeMutation extends MainMutation
{
    use OfficeProps;

    protected $attributes = [
        'name' => 'OfficeMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'area_id' => [
                'type' => Type::int()
            ],
            'name' => [
                'type' => Type::string()
            ],
            'username' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'address' => [
                'type' => Type::string()
            ],
            'phone_number' => [
                'type' => Type::string()
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
        if ($request->get('id') === auth()->user()->office_id) {
            return $request->only(
                'area_id',
                'name',
                'address',
                'phone_number',
                'description'
            )->all();
        }

        return $request->only(
            'area_id',
            'name',
            'username',
            'address',
            'phone_number',
            'description'
        )->all();
    }
}
