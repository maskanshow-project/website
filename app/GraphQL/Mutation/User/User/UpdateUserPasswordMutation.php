<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use App\User;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Http\Requests\User\v1\PasswordResetRequest;
use Closure;

class UpdateUserPasswordMutation extends BaseUserMutation
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
        return
            $args['user'] ?? false
            ? auth()->id() !== $args['user'] && $this->checkPermission('reset-password-user')
            : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(array $args = []): array
    {
        return (new PasswordResetRequest)->rules();
    }

    public function args(): array
    {
        return [
            'user' => [
                'type' => Type::string()
            ],
            'current_password' => [
                'type' => Type::string()
            ],
            'password' => [
                'type' => Type::string()
            ],
            'password_confirmation' => [
                'type' => Type::string()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        if ($args['user'] ?? false)
            return $this->updateUserPassword($args);

        else
            return $this->updateMyPassword($args);
    }

    public function updateUserPassword($args)
    {
        $user = User::findOrFail($args['user']);

        $user->tokens()->whereName('web')->delete();

        $user->update([
            'password' => \Hash::make($args['password'])
        ]);

        return [
            'status' => 200,
            'message' => 'رمز عبور با موفقیت تغییر کرد'
        ];
    }

    public function updateMyPassword($args)
    {
        if (!\Hash::check($args['current_password'], auth()->user()->password)) {
            return [
                'status' => 400,
                'message' => 'رمز عبور فعلی معتبر نمیباشد'
            ];
        }

        auth()->user()->tokens()->whereName('web')->delete();

        auth()->user()->update([
            'password' => \Hash::make($args['password'])
        ]);

        return [
            'status' => 200,
            'message' => 'رمز عبور با موفقیت تغییر کرد'
        ];
    }
}
