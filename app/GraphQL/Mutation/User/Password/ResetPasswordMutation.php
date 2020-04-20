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
use Illuminate\Support\Str;

class ResetPasswordMutation extends MainMutation
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('me');
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
        return (new ResetPasswordRequest)->rules($args, 'RESET');
    }

    public function args(): array
    {
        return [
            'token' => [
                'type' => Type::nonNull(Type::string())
            ],
            'password' => [
                'type' => Type::string(),
            ],
            'password_confirmation' => [
                'type' => Type::string(),
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        if (!$token = PasswordReset::where('token', $args['token'])->first()) {
            die(json_encode([
                'status' => 400,
                'message' => 'امکان بازیابی رمز عبور از طریق این لینک امکان پذیر نیست ، لطفا دوباره تلاش کنید'
            ]));
        }

        if (!$user = $token->user) {
            $token->delete();

            die(json_encode([
                'status' => 400,
                'message' => 'متاسفانه حساب کاربری شما از سامانه حذف شده است'
            ]));
        }

        if ($token->expired_at->isPast()) {
            $token->delete();

            die(json_encode([
                'status' => 400,
                'message' => 'لینک تغییر رمز عبور شما منقضی شده است ، لطفا دوباره تلاش کنید'
            ]));
        }

        $token->delete();

        if ($user->system_authentication_code) {
            if ($user->system_authentication_code !== request()->header('SystemAuthenticationCode')) {
                if (!Auth::user()->can('free-account-user')) {
                    die(json_encode([
                        'status' => 403,
                        'message' => 'Unauthorised'
                    ]));
                }
            }
        } else
            $user->update(['system_authentication_code' => Str::random(50)]);

        $user->tokens()->whereName('web')->delete();
        $user->update(['password' => \Hash::make($args['password'])]);

        $data = collect($user->toArray());

        $data->put('token', $user->createToken('web')->accessToken);

        return $data;
    }
}
