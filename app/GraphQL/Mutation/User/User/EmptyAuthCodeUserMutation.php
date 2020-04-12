<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\User;
use Closure;

class EmptyAuthCodeUserMutation extends BaseUserMutation
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('result');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return $this->checkPermission("empty-auth-code-user");
    }

    public function args(): array
    {
        return [
            'user' => [
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $user = User::findOrFail($args['user']);

        if (!$user->system_authentication_code) {
            return [
                'status' => 400,
                'message' => 'حساب این کاربر روی هیچ سیستمی قفل نشده است'
            ];
        }

        $user->update(['system_authentication_code' => null]);
        $user->sessions()->delete();

        return [
            'status' => 200,
            'message' => 'کد دسترسی سیستم کاربر با موفقیت حذف شد'
        ];
    }
}
