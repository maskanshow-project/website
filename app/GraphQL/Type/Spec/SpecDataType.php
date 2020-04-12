<?php

namespace App\GraphQL\Type\Spec;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Spec\SpecData;

class SpecDataType extends BaseType
{
    protected $attributes = [
        'name' => 'spec_data',
        'description' => 'A type',
        'model' => SpecData::class
    ];

    public function get_fields()
    {
        return [
            'data' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'estate_id' => [
                'type' => Type::string(),
            ],
            'values' => [
                'type' => Type::listOf(\GraphQL::type('spec_default')),
            ],
            'row' => $this->relationItemField('spec_row', 'is_active', 'read-spec')
        ];
    }
}
