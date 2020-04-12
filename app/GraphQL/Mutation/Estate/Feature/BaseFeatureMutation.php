<?php

namespace App\GraphQL\Mutation\Estate\Feature;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Estate\FeatureProps;

abstract class BaseFeatureMutation extends MainMutation
{
    use FeatureProps;

    protected $attributes = [
        'name' => 'FeatureMutation',
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
            'estate_types' => [
                'type' => Type::listOf(Type::int())
            ],
            'is_detailable' => [
                'type' => Type::boolean()
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
     * @param Model $feature
     * @return void
     */
    public function afterCreate($request, $feature)
    {
        $feature->estate_types()->attach($request->get('estate_types'));
    }

    /**
     * The function that get the model and run after the model was updated
     *
     * @param Request $request
     * @param Model $feature
     * @return void
     */
    public function afterUpdate($request, $feature)
    {
        $feature->estate_types()->sync($request->get('estate_types'));
    }
}
