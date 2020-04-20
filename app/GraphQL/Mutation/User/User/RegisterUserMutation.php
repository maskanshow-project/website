<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\RegisterRequest;
use App\User;
use Closure;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\UploadType;

class RegisterUserMutation extends BaseUserMutation
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('me');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(array $args = []): array
    {
        return (new RegisterRequest)->rules($args, 'REGISTER');
    }

    public function args(): array
    {
        return [
            'city_id' => [
                'type' => Type::int()
            ],
            'first_name' => [
                'type' => Type::string()
            ],
            'last_name' => [
                'type' => Type::string()
            ],
            'username' => [
                'type' => Type::string()
            ],
            'email' => [
                'type' => Type::string()
            ],
            'password' => [
                'type' => Type::string()
            ],
            'phone_number' => [
                'type' => Type::string()
            ],
            'address' => [
                'type' => Type::string()
            ],
            'password_confirmation' => [
                'type' => Type::string()
            ],
            'avatar' => [
                'type' => \GraphQL::type('Upload')
            ],
            'national_code' => [
                'type' => Type::string()
            ],
            'gender' => [
                'type' => Type::boolean()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $user = User::create(array_merge(
            $this->getRequest(collect($args)),
            [
                'password' => bcrypt($args['password'])
            ]
        ));

        auth()->login($user);

        auth()->user()->update(['system_authentication_code' => Str::random(50)]);

        $data = collect($user->toArray());

        $data->put('token', $user->createToken('web')->accessToken);

        return $data;
    }
}
