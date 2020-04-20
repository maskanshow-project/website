<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\RegisterRequest;
use App\User;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\UploadType;
use App\Http\Requests\v1\RegisterConsultantRequest;
use App\Models\User\Office;
use Illuminate\Support\Str;
use Closure;

class RegisterConsultantMutation extends BaseUserMutation
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
        return (new RegisterConsultantRequest)->rules($args, 'REGISTER');
    }

    public function args(): array
    {
        return [
            // User info
            'city_id' => [
                'type' => Type::int()
            ],
            'area_id' => [
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
            'phone_number' => [
                'type' => Type::string()
            ],
            'address' => [
                'type' => Type::string()
            ],
            'password' => [
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
            'reagent_code' => [
                'type' => Type::string()
            ],

            // Office info
            'office_name' => [
                'type' => Type::string()
            ],
            'office_username' => [
                'type' => Type::string()
            ],
            'office_description' => [
                'type' => Type::string()
            ],
            'office_address' => [
                'type' => Type::string()
            ],
            'office_phone_number' => [
                'type' => Type::string()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $parent_id = $args['reagent_code'] ?? false ? User::whereUsername($args['reagent_code'])->first()->id ?? null : null;

        $user = User::create(array_merge(
            $this->getRequest(collect($args)),
            [
                'parent_id' => $parent_id,
                'password' => bcrypt($args['password'])
            ]
        ));

        $office = $user->offices()->create([
            'name' => $args['office_name'] ?? null,
            'area_id' => $args['area_id'] ?? null,
            'username' => $args['office_username'] ?? null,
            'description' => $args['office_description'] ?? null,
            'address' => $args['office_address'] ?? null,
            'phone_number' => $args['office_phone_number'] ?? null,
        ]);

        $user->update(['office_id' => $office->id]);

        $user->attachRole('consultant');

        auth()->login($user);

        auth()->user()->update(['system_authentication_code' => Str::random(50)]);

        $data = collect($user->toArray());

        $data->put('token', $user->createToken('web')->accessToken);

        return $data;
    }
}
