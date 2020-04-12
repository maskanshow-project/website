<?php

namespace App\GraphQL\Mutation\User\AccessCode;

use App\GraphQL\Mutation\MainMutation;
use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\User\ChangeCreditRequest;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\User;
use App\Models\User\AccessCode;
use App\Models\User\ActiveSession;
use Carbon\Carbon;
use Closure;

class LoginWithAccessCodeMutation extends MainMutation
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

    public function args(): array
    {
        return [
            'username' => [
                'type' => Type::nonNull(Type::string())
            ],
            'access_code' => [
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $code = AccessCode::whereCode($args['access_code'])->with('user')->first();

        if (!$code) {
            die(json_encode([
                'status' => 400,
                'message' => 'کد دسترسی شما معتبر نمیباشد'
            ]));
        }

        if ($code->expired_at->isPast()) {
            $code->delete();

            die(json_encode([
                'status' => 400,
                'message' => 'متاسفانه کد دسترسی شما منقضی شده است ، جهت دریافت کد دسترسی جدید با پشتیبانی تماس بگیرید'
            ]));
        }

        $user = $code->user;

        if ($user->username !== strtolower($args['username'])) {
            $code->delete();

            die(json_encode([
                'status' => 400,
                'message' => 'متاسفانه نام کاربری شما اشتباه وارد شده و کد دسترسی شما منقضی گشت ، جهت دریافت کد جدید با پشتیبانی سایت تماس بگیرید'
            ]));
        }

        $code->delete();

        ActiveSession::create([
            'user_id' => $user->id,
            'user_agent' => request()->header('User-Agent'),
            'created_at' => now()
        ]);

        $data = collect($user->toArray());
        $data->put('token', $user->createToken('web')->accessToken);

        return $data;
    }
}
