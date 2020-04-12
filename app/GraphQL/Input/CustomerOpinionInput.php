<?php

namespace App\GraphQL\Input;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\UploadType;

class CustomerOpinionInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'customer_opinion_input',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'avatar' => [
                'type' => \GraphQL::type('Upload')
            ],
            'post' => [
                'type' => Type::string()
            ],
            'fullname' => [
                'type' => Type::string()
            ],
            'opinion' => [
                'type' => Type::string()
            ],
        ];
    }
}
