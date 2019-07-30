<?php

namespace App\GraphQL\Mutation\User\User;

use GraphQL\Type\Definition\Type;
use App\Http\Requests\v1\User\ChangeCreditRequest;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\User;
use Carbon\Carbon;

class ChangeCreditUserMutation extends BaseUserMutation
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
        return $this->checkPermission("change-credit-user");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(array $args = [])
    {
        return ( new ChangeCreditRequest )->rules($args, 'UPDATE');
    }

    public function args()
    {
        return [
            'id' => [
                'type' => Type::nonNull( Type::string() )
            ],
            'count' => [
                'type' => Type::int()
            ],
            'registered' => [
                'type' => Type::int()
            ],
            'days' => [
                'type' => Type::int()
            ],
            'type' => [
                'type' => Type::boolean(),
            ]
        ];
    }
   
    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $user = User::findOrFail( $args['id'] ?? null );

        if (
            !$args['type'] &&
            (
                $user->remaining_hits_count <= $args['count']
             || $user->remaining_registered_count <= $args['registered']
            )
        ) {
            return [
                'status' => 400,
                'message' => 'اعتبار فعلی کابر کمتر از مقدار درخواستی شما جهت کاهش است'
            ];
        }

        $user->update([
            'validity_end_at' => ( new Carbon( $user->validity_end_at ) )->{$args['type'] ? 'addDays' : 'subDays'}( $args['days'] )
        ]);

        $user->{$args['type'] ? 'increment' : 'decrement'}('remaining_hits_count', $args['count'] );
        $user->{$args['type'] ? 'increment' : 'decrement'}('remaining_registered_count', $args['registered'] );

        return [
            'status' => 200,
            'message' => 'اعتبار کاربر با موفقیت به مقدار مشاهده ' . $args['count'] . ' ملک و ثبت '  . $args['registered'] . ' و ملک' . $args['days'] . ' روز ' . ($args['type'] ? 'افزایش' : 'کاهش') . ' پیدا کرد'
        ];
    }
}