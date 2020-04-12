<?php

namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\Traits\CheckPermissions;
use Illuminate\Support\Str;

abstract class BaseType extends GraphQLType
{
    use CheckPermissions;

    public function fields(): array
    {
        return array_merge(
            [
                'id' => [
                    'type' => $this->incrementing ?? true ? Type::int() : Type::string()
                ],
                'created_at' => [
                    'type' => Type::string()
                ],
                'updated_at' => [
                    'type' => Type::string()
                ]
            ],

            $this->get_fields()
        );
    }

    public function infoField(String $field = 'title')
    {
        return [
            $field => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ]
        ];
    }

    public function audits($type)
    {
        return [
            'type' => Type::listOf(\GraphQL::type('audit')),
            'query' => function (array $args, $query) {
                return $query->orderBy('created_at', 'desc')->limit(5);
            },
            'privacy' => function () use ($type) {
                return $this->checkPermission("see-log-{$type}");
            }
        ];
    }

    public function creator($type)
    {
        return [
            'type' => \GraphQL::type('user'),
            'privacy' => function () use ($type) {
                return $this->checkPermission("see-creator-{$type}");
            }
        ];
    }

    public function votes()
    {
        return [
            'type' => \GraphQL::type('votes'),
            'resolve' => function ($data) {
                return [
                    'likes' => $data->dislikesCount,
                    'dislikes' => $data->dislikesCount
                ];
            },
            'selectable' => false
        ];
    }

    public function relationListField($type, $acceptable_field = 'is_active', $permission = null, $ordering = 'desc')
    {
        return [
            'type'  => Type::listOf(\GraphQL::type($type)),
            'query' => $this->getRelationQuery($type, $acceptable_field, $permission, null, $ordering)
        ];
    }

    public function paginatedRelationListField($type, $acceptable_field = 'is_active', $permission = null)
    {
        return [
            'type'  => Type::listOf(\GraphQL::type($type)),
            'query' => $this->getRelationQuery($type, $acceptable_field, $permission, true)
        ];
    }

    public function relationItemField($type, $acceptable_field = 'is_active', $permission = null)
    {
        return [
            'type'  => \GraphQL::type($type),
            'query' => $this->getRelationQuery($type, $acceptable_field, $permission)
        ];
    }

    public function getRelationQuery($type, $acceptable_field, $permission = null, $paginated = false, $ordering = 'desc')
    {
        $permission = $permission ? $permission : "read-{$type}";

        return function (array $args, $query) use ($type, $acceptable_field, $permission, $paginated, $ordering) {

            if (!$this->checkPermission($permission))
                $query->where(Str::plural($type) . ".{$acceptable_field}", 1);

            if ($paginated)
                $query->offset((($args['page'] ?? 1) - 1) * 10)->take(10);

            return $query->orderBy(Str::plural($type) . ".created_at", $ordering);
        };
    }

    public function acceptableField($type)
    {
        return [
            'type' => Type::boolean(),
            'privacy' => function () use ($type) {
                return $this->checkPermission("read-{$type}");
            }
        ];
    }

    public function imageField($field = 'logo')
    {
        return [
            'type' => \GraphQL::type('single_media'),
            'resolve' => function ($data) use ($field) {
                return $data->$field[0] ?? null;
            }
        ];
    }

    public function isMineField()
    {
        return [
            'type' => Type::boolean(),
            'resolve' => function ($data) {
                return $data->user_id === auth()->id() ?? false;
            }
        ];
    }
}
