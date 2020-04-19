<?php

namespace App\GraphQL\Mutation\Financial\Promocode;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\Financial\PromocodeProps;

abstract class BasePromocodeMutation extends MainMutation
{
    use PromocodeProps;

    protected $attributes = [
        'name' => 'PromocodeMutation',
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
            'code' => [
                'type' => Type::string()
            ],
            'cost' => [
                'type' => Type::int()
            ],
            'quantity' => [
                'type' => Type::int()
            ],
            'remainig_count' => [
                'type' => Type::int()
            ],
            'expired_at' => [
                'type' => Type::string()
            ],
            'plans' => [
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
     * @param Model $promocode
     * @return void
     */
    public function afterCreate($request, $promocode)
    {
        $promocode->plans()->attach($request->get('plans'));
    }

    /**
     * The function that get the model and run after the model was updated
     *
     * @param Request $request
     * @param Model $promocode
     * @return void
     */
    public function afterUpdate($request, $promocode)
    {
        $promocode->plans()->sync($request->get('plans'));
    }
}
