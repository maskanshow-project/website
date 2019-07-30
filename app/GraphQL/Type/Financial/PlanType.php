<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Financial\Plan;

class PlanType extends BaseType
{    
    protected $attributes = [
        'name' => 'PlanType',
        'description' => 'A type',
        'model' => Plan::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('plan'),
            'color' => [
                'type' => Type::string(),
            ],
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'price' => [
                'type' => Type::int(),
            ],
            'visited_estate_count' => [
                'type' => Type::int(),
            ],
            'registered_estate_count' => [
                'type' => Type::int()
            ],
            'credit_days_count' => [
                'type' => Type::int(),
            ],
            'audits' => $this->audits('plan'),
            'is_active' => $this->acceptableField('plan')
        ];
    }
}