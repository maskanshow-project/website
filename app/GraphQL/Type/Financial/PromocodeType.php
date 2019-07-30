<?php

namespace App\GraphQL\Type\Estate;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Financial\Promocode;

class PromocodeType extends BaseType
{    
    protected $attributes = [
        'name' => 'PromocodeType',
        'description' => 'A type',
        'model' => Promocode::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('promocode'),
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'code' => [
                'type' => Type::string(),
            ],
            'cost' => [
                'type' => Type::int(),
            ],
            'quantity' => [
                'type' => Type::int(),
            ],
            'used_count' => [
                'type' => Type::int(),
            ],
            'expired_at' => [
                'type' => Type::string(),
            ],
            'plans' => $this->relationListField('plan'),
            'audits' => $this->audits('promocode'),
            'is_active' => $this->acceptableField('promocode')
        ];
    }
}