<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use App\GraphQL\Props\User\UserProps;
use Rebing\GraphQL\Support\UploadType;

abstract class BaseUserMutation extends MainMutation
{
    use UserProps;

    protected $incrementing = false;

    protected $attributes = [
        'name' => 'UserMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'city_id' => [
                'type' => Type::int()
            ],
            'first_name' => [
                'type' => Type::string()
            ],
            'last_name' => [
                'type' => Type::string()
            ],
            'username' => [
                'type' => Type::string()
            ],
            'email' => [
                'type' => Type::string()
            ],
            'password' => [
                'type' => Type::string()
            ],
            'password_confirmation' => [
                'type' => Type::string()
            ],
            'avatar' => [
                'type' => \GraphQL::type('Upload')
            ],
            'phone_number' => [
                'type' => Type::string()
            ],
            'address' => [
                'type' => Type::string()
            ],
            'national_code' => [
                'type' => Type::string()
            ],
            'gender' => [
                'type' => Type::boolean()
            ],
            'roles' => [
                'type' => Type::listOf(Type::int())
            ],
            'is_deleted_image' => [
                'type' => Type::boolean()
            ],
            'is_public_info' => [
                'type' => Type::boolean()
            ],
            'permissions' => [
                'type' => Type::listOf(Type::int())
            ],
            // 'is_active' => [
            //     'type' => Type::boolean()
            // ]
        ];
    }

    /**
     * The function that get the model and run after the model was updated
     *
     * @param Request $request
     * @param Model $product
     * @return void
     */
    public function afterUpdate($request, $user)
    {
        if (auth()->id() !== $request->get('id')) {
            $user->syncPermissions($request->get('permissions', []));
            $user->syncRoles($request->get('roles', []));
        }
    }

    /**
     * Get the portion of request class
     *
     * @param Request $request
     * @return Array $request
     */
    public function getRequest($request)
    {
        if (auth()->check() && $request->get('id') === auth()->id()) {
            return $request->only(
                'city_id',
                'first_name',
                'last_name',
                'address',
                'phone_number',
                'email',
                'national_code',
                'gender',
                'is_public_info'
            )->all();
        }

        return array_merge($request->only(
            'city_id',
            'first_name',
            'last_name',
            'address',
            'phone_number',
            'email',
            'national_code',
            'gender'
        )->all(), [
            'username' => strtolower($request['username'])
        ]);
    }
}
