<?php

namespace App\GraphQL\Type\User;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\User;

class MeType extends BaseType
{
    protected $incrementing = false;

    protected $attributes = [
        'name' => 'me',
        'description' => 'A type',
        'model' => User::class
    ];

    public function get_fields()
    {
        return [
            'first_name' => [
                'type' => Type::string()
            ],
            'last_name' => [
                'type' => Type::string()
            ],
            'full_name' => [
                'type' => Type::string(),
                'resolve'       => function ($data) {
                    return $data->full_name;
                },
                'selectable'    => false,
            ],
            'city' => [
                'type' => \GraphQL::type('city'),
            ],
            'username' => [
                'type' => Type::string(),
            ],
            'email' => [
                'type' => Type::string(),
            ],
            'phone_number' => [
                'type' => Type::string(),
            ],
            'address' => [
                'type' => Type::string(),
            ],
            'gender' => [
                'type' => Type::boolean(),
            ],
            'avatar' => $this->imageField('avatar'),
            'national_code' => [
                'type' => Type::string(),
            ],
            'is_public_info' => [
                'type' => Type::boolean(),
            ],
            'can_has_member' => [
                'type' => Type::boolean(),
            ],
            'visited_estate_count' => [
                'type' => Type::int(),
            ],
            'remaining_hits_count' => [
                'type' => Type::int(),
            ],
            'registered_estate_count' => [
                'type' => Type::int(),
            ],
            'remaining_registered_count' => [
                'type' => Type::int(),
            ],
            'payments_count' => [
                'type' => Type::int(),
            ],
            'total_payments' => [
                'type' => Type::int(),
            ],
            'validity_end_at' => [
                'type' => Type::string(),
            ],
            'last_payment' => [
                'type' => Type::string(),
            ],
            'favorites' => $this->relationListField('estate'),
            'comments' => [
                'type' => Type::listOf(\GraphQL::type("comment")),
                'query' => function ($args, $query) {
                    return $query->offset((($args['page'] ?? 1) - 1) * 10)->take(10);
                }
            ],
            'workplace' => [
                'type' => \GraphQL::type('office')
            ],
            'offices' => [
                'type' => Type::listOf(\GraphQL::type('office'))
            ],
            'roles' => $this->relationListField('role'),
            'parent' => [
                'type' => \GraphQL::type('user'),
            ],
            'plan' => $this->relationItemField('plan'),
            'messages' => [
                'type' => Type::listOf(\GraphQL::type('message')),
                'selectable' => false,
                'resolve' => function ($data) {
                    $allMessages = $data->load([
                        'roles:id',
                        'roles.messages' => function ($query) {
                            return $query->where('expired_at', '>', now());
                        }
                    ])->roles;

                    $messages = [];

                    foreach ($allMessages as $item)
                        $messages[] = $item->messages;

                    return collect($messages)->flatten(1);
                },
            ],
            'members' => [
                'type' => Type::listOf(\GraphQL::type('user')),
            ],
            'permissions' => [
                'type' => Type::listOf(\GraphQL::type('permission')),
            ],
            'allPermissions' => [
                'type' => Type::listOf(\GraphQL::type('permission')),
                'selectable' => false,
                'resolve' => function ($data) {
                    return $data->allPermissions();
                }
            ],
            'token' => [
                'type' => Type::string(),
                'selectable' => false
            ],
            'system_authentication_code' => [
                'type' => Type::string(),
                'selectable' => false
            ],
        ];
    }
}
