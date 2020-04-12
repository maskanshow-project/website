<?php

namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;

class ChartRecordType extends GraphQLType
{
    protected $attributes = [
        'name' => 'chart_record',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'month' => [
                'type' => Type::string()
            ],
            'count' => [
                'type' => Type::int()
            ],
        ];
    }
}
