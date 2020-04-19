<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Estate\Assignment;

class AssignmentType extends BaseType
{
    protected $attributes = [
        'name' => 'assignment',
        'description' => 'A type',
        'model' => Assignment::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('assignment'),
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'similar_titles' => [
                'type' => Type::listOf(Type::string()),
                'selectable' => false
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'color' => [
                'type' => Type::string()
            ],
            'has_sales_price' => [
                'type' => Type::boolean()
            ],
            'has_mortgage_price' => [
                'type' => Type::boolean()
            ],
            'has_rental_price' => [
                'type' => Type::boolean()
            ],
            'estate_types' => $this->relationListField('estate_type'),
            'audits' => $this->audits('assignment'),
            'is_active' => $this->acceptableField('assignment')
        ];
    }
}
