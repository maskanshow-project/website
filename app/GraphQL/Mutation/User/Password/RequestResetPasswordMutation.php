<?php

namespace App\GraphQL\Mutation\User\Password;

use App\GraphQL\Mutation\MainMutation;
use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\User\ResetPasswordRequest;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Mail\ResetPassword;
use App\Models\User\PasswordReset;
use App\User;
use Closure;
use DB;
use Mail;

class RequestResetPasswordMutation extends MainMutation
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
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(array $args = []): array
    {
        return (new ResetPasswordRequest)->rules($args, 'REQUEST');
    }

    public function args(): array
    {
        return [
            'email' => [
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        if (!$user = User::whereEmail($args['email'])->first()) {
            return [
                'status' => 400,
                'message' => 'آدرس ایمیل مورد نظر شما یافت نشد'
            ];
        }

        PasswordReset::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'token' => $token = str_random(100),
            'expired_at' => now()->addDay(1)
        ]);

        Mail::to($user->email)->send(new ResetPassword($user, $token));

        return [
            'status' => 200,
            'message' => "لینک تغییر رمز عبور با موفقیت به ایمیل شما به آدرس {$user->email} ارسال شد ، درصورت پیدا نکردن ایمیل لطفا پوشه spam خود را نیز چک کنید ."
        ];
    }
}
