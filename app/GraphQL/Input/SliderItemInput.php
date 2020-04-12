<?php

namespace App\GraphQL\Input;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\UploadType;

class SliderItemInput extends GraphQLType
{
    protected $inputObject = true;

    protected $attributes = [
        'name' => 'slider_item_input',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'image' => [
                'type' => \GraphQL::type('Upload')
            ],
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'button' => [
                'type' => Type::string()
            ],
            'link' => [
                'type' => Type::string()
            ],
            'delete_image' => [
                'type' => Type::boolean()
            ],
        ];
    }
}
