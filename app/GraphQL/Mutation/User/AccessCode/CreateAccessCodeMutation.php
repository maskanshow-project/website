<?php

namespace App\GraphQL\Mutation\User\AccessCode;

use App\GraphQL\Mutation\MainMutation;
use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\User\ChangeCreditRequest;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\User;
use App\Models\User\AccessCode;
use Carbon\Carbon;

class CreateAccessCodeMutation extends MainMutation
{
    public function type()
    {   
        return \GraphQL::type('result');
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(array $args)
    {
        return $this->checkPermission("create-access-code");
    }

    public function args()
    {
        return [
            'user' => [
                'type' => Type::nonNull( Type::string() )
            ],
        ];
    }
   
    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $user = User::findOrFail( $args['user'] ?? null );
        
        if ( !$user->system_authentication_code )
        {
            return [
                'status' => 400,
                'message' => "تا زمانی که حساب روی سیستمی قفل نشود امکان ساخت کد دسترسی وجود ندارد"
            ];
        }

        AccessCode::create([
            'user_id' => $user->id,
            'code' => $code = str_random(8),
            'expired_at' => now()->addMinutes(30)
        ]);

        return [
            'code' => $code,
            'status' => 200,
            'message' => "کد دسترسی برای حساب مورد نظر با موفقیت ساخته شد ، اعتبار کد تنها ۳۰ دقیقه میباشد"
        ];
    }
}