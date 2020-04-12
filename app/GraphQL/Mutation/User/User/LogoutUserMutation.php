<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\LoginRequest;
use Auth;
use Closure;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use function GuzzleHttp\json_encode;

class LogoutUserMutation extends BaseUserMutation
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('result');
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        auth()->user()->token()->delete();

        return [
            'status' => 200,
            'message' => 'با موفقیت از سیستم خارج شدید'
        ];
    }
}
