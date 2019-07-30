<?php

namespace App\GraphQL\Query\Shop\Estate;

use App\GraphQL\Helpers\AllQuery;
use GraphQL\Type\Definition\Type;

class EstatesQuery extends BaseEstateQuery
{
    use AllQuery;

    protected $has_more_args = true;

    public function get_args()
    {
        return [
            'code' => [
                'type' => Type::string()
            ],
            'consultant' => [
                'type' => Type::int()
            ],
            'landloard_fullname' => [
                'type' => Type::string()
            ],
            'landloard_phone_number' => [
                'type' => Type::string()
            ],
            'created_at' => [
                'type' => Type::string(),
                'rules' => 'date_format:Y-m-d'
            ],
            'areas' => [
                'type' => Type::listOf( Type::int() )
            ],
            'streets' => [
                'type' => Type::listOf( Type::int() )
            ],
            'assignment' => [
                'type' => Type::int()
            ],
            'estate_type' => [
                'type' => Type::int()
            ],
            'features' => [
                'type' => Type::listOf( Type::int() )
            ],
            'dynamic_filters' => [
                'type' => Type::listOf( \GraphQL::type('dynamic_filter_input') )
            ],
            'area' => [
                'type' => \GraphQL::type('range_input')
            ],
            'rental_price' => [
                'type' => \GraphQL::type('range_input')
            ],
            'mortgage_price' => [
                'type' => \GraphQL::type('range_input')
            ],
            'sales_price' => [
                'type' => \GraphQL::type('range_input')
            ],
            'is_active' => [
                'type' => Type::boolean()
            ],
            'is_assignmented' => [
                'type' => Type::boolean()
            ],
            'has_assignment_request' => [
                'type' => Type::boolean()
            ],
            'has_reject_reason' => [
                'type' => Type::boolean()
            ],
            'has_advantages' => [
                'type' => Type::boolean()
            ],
            'has_disadvantages' => [
                'type' => Type::boolean()
            ],
            'has_video' => [
                'type' => Type::boolean()
            ],
            'registered_by_me' => [
                'type' => Type::boolean()
            ],
            'has_table' => [
                'type' => Type::boolean()
            ],
        ];
    }
}