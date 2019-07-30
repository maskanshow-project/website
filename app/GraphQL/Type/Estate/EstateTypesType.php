<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Estate\EstateType;

class EstateTypesType extends BaseType
{    
    protected $attributes = [
        'name' => 'EstateTypesType',
        'description' => 'A type',
        'model' => EstateType::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('estate_type'),
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'icon' => [
                'type' => Type::string(),
            ],
            'spec' => $this->relationItemField('spec'),
            'assignments' => $this->relationListField('assignment'),
            'features' => $this->relationListField('feature'),
            'audits' => $this->audits('estate_type'),
            'is_active' => $this->acceptableField('estate_type')
        ];
    }
}