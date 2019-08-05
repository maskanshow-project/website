<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\LoginRequest;
use Auth;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use function GuzzleHttp\json_encode;

class LoginUserMutation extends BaseUserMutation
{
    public function type()
    {
        return \GraphQL::type('me');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(array $args = [])
    {
        return ( new LoginRequest )->rules($args, 'LOGIN');
    }

    public function args()
    {
        return [
            'username'  => [
                'type' => Type::string()
            ],
            'password'  => [
                'type' => Type::string()
            ]
        ];
    }
   
    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {   
        if ( Auth::attempt([
            'username' => strtolower( $args['username'] ),
            'password' => $args['password']
        ]) )
        {
            if ( Auth::user()->system_authentication_code )
            {
                if ( Auth::user()->system_authentication_code !== request()->header('SystemAuthenticationCode') )
                {
                    if ( !Auth::user()->can('free-account-user') )
                    {
                        die(json_encode([
                            'status' => 403,
                            'message' => 'Unauthorised'
                        ]));
                    }
                }
            }
            else
                auth()->user()->update([ 'system_authentication_code' => str_random(50) ]);


            $data = collect( Auth::user()->toArray() );
            
            $data->put('token', Auth::user()->createToken('web')->accessToken);

            return $data;
        } 
        else
        {
            die(json_encode([
                'status' => 400,
                'message' => 'نام کاربری یا رمز عبور شما اشتباه است'
            ]));
        }
    }
}