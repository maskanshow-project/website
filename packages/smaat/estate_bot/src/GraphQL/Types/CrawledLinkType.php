<?php

declare(strict_types=1);

namespace SmaaT\EstateBot\GraphQL\Types;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use SmaaT\EstateBot\Models\CrawledLink;

class CrawledLinkType extends GraphQLType
{
    protected $attributes = [
        'name' => 'crawled_link',
        'description' => 'A type',
        'model' => CrawledLink::class
    ];

    public function fields(): array
    {
        return [
            'id'     => [
                'type' => Type::int()
            ],
            'estate'     => [
                'type' => GraphQL::type('estate')
            ],
            'estate_id'     => [
                'type' => Type::int()
            ],
            'provider'      => [
                'type' => Type::string()
            ],
            'link'      => [
                'type' => Type::string()
            ],
            'errors'        => [
                'type' => Type::listOf(Type::string())
            ],
            'crawled_at'        => [
                'type' => Type::string()
            ],
        ];
    }

    protected function resolveErrorsField($root, $args)
    {
        return collect($root->errors)->flatten(1);
    }
}
