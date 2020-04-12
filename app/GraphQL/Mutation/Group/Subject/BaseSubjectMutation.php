<?php

namespace App\GraphQL\Mutation\Group\Subject;

use GraphQL\Type\Definition\Type;
use App\GraphQL\Mutation\MainMutation;
use Rebing\GraphQL\Support\UploadType;
use App\GraphQL\Props\Group\SubjectProps;
use App\GraphQL\Mutation\Group\GroupTags;

abstract class BaseSubjectMutation extends MainMutation
{
    use SubjectProps;

    protected $attributes = [
        'name' => 'SubjectMutation',
        'description' => 'A mutation'
    ];

    public function get_args()
    {
        return [
            'parent_id' => [
                'type' => Type::int()
            ],
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'icon' => [
                'type' => Type::string()
            ],
            'is_deleted_image' => [
                'type' => Type::boolean()
            ],
            'logo' => [
                'type' => \GraphQL::type('Upload')
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
     * @param Model $group
     * @return void
     */
    public function afterCreate($request, $group)
    {
        $group->attachTags($request->get('keywords'));
    }

    /**
     * The function that get the model and run after the model was updated
     *
     * @param Request $request
     * @param Model $group
     * @return void
     */
    public function afterUpdate($request, $group)
    {
        $group->syncTags($request->get('keywords'));
    }
}
