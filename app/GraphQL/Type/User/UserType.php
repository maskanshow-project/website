<?php

namespace App\GraphQL\Type\User;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use App\User;

class UserType extends BaseType
{
    protected $incrementing = false;

    protected $attributes = [
        'name' => 'UserType',
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
                'privacy' => function() {
                    return $this->checkPermission("see-address-user");
                },
            ],
            'email' => [
                'type' => Type::string(),
                'privacy' => function($data) {
                    return $this->checkPermission("see-details-user");
                },
            ],
            'username' => [
                'type' => Type::string(),
            ],
            'phone_number' => [
                'type' => Type::string(),
                'privacy' => function() {
                    return $this->checkPermission("see-phone-number-user");
                },
            ],
            'address' => [
                'type' => Type::string(),
                'privacy' => function() {
                    return $this->checkPermission("see-address-user");
                },
            ],
            'avatar' => $this->imageField('avatar'),
            'national_code' => [
                'type' => Type::string(),
                'privacy' => function() {
                    return $this->checkPermission("see-details-user");
                },
            ],
            'gender' => [
                'type' => Type::boolean(),
            ],
            'visited_estate_count' => [
                'type' => Type::int(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'remaining_hits_count' => [
                'type' => Type::int(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'registered_estate_count' => [
                'type' => Type::int(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'remaining_registered_count' => [
                'type' => Type::int(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'validity_end_at' => [
                'type' => Type::string(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'payments_count' => [
                'type' => Type::int(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'total_payments' => [
                'type' => Type::int(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'last_payment' => [
                'type' => Type::string(),
                'privacy' => function() {
                    return $this->checkPermission("see-credit-user");
                },
            ],
            'favorites' => $this->relationListField('estate'),
            'comments' => [
                'type' => Type::listOf( \GraphQL::type("comment") ),
                'privacy' => function() {
                    return $this->checkPermission("read-comment");
                },
                'query' => function($args, $query) {
                    return $query->offset( (($args['page'] ?? 1 ) - 1) * 10 )->take(10);
                }
            ],
            'workplace' => [
                'type' => \GraphQL::type('office')
            ],
            'offices' => [
                'type' => Type::listOf( \GraphQL::type('office') )
            ],
            'roles' => $this->relationListField('role'),
            'permissions' => [
                'type' => Type::listOf( \GraphQL::type('permission') )
            ],
            'used_at' => [
                'type' => Type::string(),
                'selectable' => false,
                'resolve' => function($data) {
                    return $data->pivot->used_at ?? null;
                }
            ],
            'is_public_info' => [
                'type' => Type::boolean(),
            ],
            'can_has_member' => [
                'type' => Type::boolean(),
                'privacy' => function() {
                    return $this->checkPermission("active-user");
                }
            ],
            'plan' => $this->relationItemField('plan'),
            'parent' => [
                'type' => \GraphQL::type('user'),
            ],
            'members' => [
                'type' => Type::listOf( \GraphQL::type('user') ),
            ],
            'sessions' => [
                'type' => Type::listOf( \GraphQL::type('session') ),
                'privacy' => function() {

                    return $this->checkPermission("see-sessions-user");
                },
                'query' => function($args, $query) {
                    
                    return $query->orderBy('created_at', 'desc');
                }
            ],
            'audits' => $this->audits('user'),
        ];
    }
}