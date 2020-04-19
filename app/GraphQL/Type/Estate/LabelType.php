<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Estate\Label;

class LabelType extends BaseType
{
    protected $attributes = [
        'name' => 'label',
        'description' => 'A type',
        'model' => Label::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('label'),
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'color' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'audits' => $this->audits('label'),
            'is_active' => $this->acceptableField('label')
        ];
    }
}
