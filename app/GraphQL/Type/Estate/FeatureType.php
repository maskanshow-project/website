<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Estate\Feature;

class FeatureType extends BaseType
{    
    protected $attributes = [
        'name' => 'FeatureType',
        'description' => 'A type',
        'model' => Feature::class
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
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'icon' => [
                'type' => Type::string()
            ],
            'is_detailable' => [
                'type' => Type::boolean()
            ],
            'audits' => $this->audits('assignment'),
            'estate_types' => $this->relationListField('estate_type'),
            'is_active' => $this->acceptableField('assignment')
        ];
    }
}