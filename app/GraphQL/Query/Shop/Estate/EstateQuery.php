<?php

namespace App\GraphQL\Query\Shop\Estate;

use App\GraphQL\Helpers\SingleQuery;
use GraphQL\Type\Definition\Type;

class EstateQuery extends BaseEstateQuery
{
    use SingleQuery;

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull($this->incrementing ? Type::int() : Type::string())
            ],
            'only_show_valid_spec' => [
                'type' => Type::boolean()
            ]
        ];
    }
}
