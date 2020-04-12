<?php

namespace App\GraphQL\Type\Spec;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\Models\Spec\SpecHeader;

class SpecHeaderType extends BaseType
{
    protected $attributes = [
        'name' => 'spec_header',
        'description' => 'A type',
        'model' => SpecHeader::class
    ];

    public function get_fields()
    {
        return [
            'is_mine' => $this->isMineField(),
            'creator' => $this->creator('spec'),
            'title' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'description' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            // 'rows' => $this->relationListField('spec_row', 'is_active', 'read-spec', 'asc'),
            'rows' => [
                'type'  => Type::listOf(\GraphQL::type('spec_row')),
                'query' => function (array $args, $query) {

                    return $query->when($args['only_show_valid_spec'] ?? false, function ($query) use ($args) {

                        $query->whereHas('data', function ($query) use ($args) {

                            $query->where('estate_id', $args['id'] ?? false);
                        });
                    })
                        ->orderBy("spec_rows.created_at", 'asc');
                }
            ],
            'audits' => $this->audits('spec'),
            'is_active' => $this->acceptableField('spec')
        ];
    }
}
