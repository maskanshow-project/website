<?php

namespace App\GraphQL\Mutation\Estate\EstateType;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Estate\EstateTypeProps;

abstract class BaseEstateTypeMutation extends MainMutation
{
    use EstateTypeProps;

    protected $attributes = [
        'name' => 'EstateTypeMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'icon' => [
                'type' => Type::string()
            ],
            'assignments' => [
                'type' => Type::listOf(Type::int())
            ],
            'is_active' => [
                'type' => Type::boolean()
            ]
        ];
    }

    /**
     * The function that get the model and run after the model was created
     *
     * @param Request $request
     * @param Model $estate_type
     * @return void
     */
    public function afterCreate($request, $estate_type)
    {
        $estate_type->assignments()->attach($request->get('assignments'));
    }

    /**
     * The function that get the model and run after the model was updated
     *
     * @param Request $request
     * @param Model $estate_type
     * @return void
     */
    public function afterUpdate($request, $estate_type)
    {
        $estate_type->assignments()->sync($request->get('assignments'));
    }
}
