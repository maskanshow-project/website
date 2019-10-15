<?php

namespace App\GraphQL\Mutation\Estate\Estate;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\Estate\Estate;

class AssignmentEstateMutation extends BaseEstateMutation
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
        if ( $this->checkPermission("accept-assignment-estate") )
            return true;
        
        elseif ( auth()->user()->visitedEstates()->where('id', $args['estate'] ?? false)->count() )
            return true;

        elseif ( (Estate::find($args['estate'])->user_id ?? false) === auth()->id() )
            return true;
    }

    public function args()
    {
        return [
            'estate' => [
                'type' => Type::nonNull( Type::string() )
            ],
        ];
    }
   
    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $estate = Estate::findOrFail( $args['estate'] );

        if ( $estate->assignmented_at )
        {
            return [
                'status' => 400,
                'message' => 'این ملک قبلا واگذار شده است'
            ];
        }

        if ( $estate->assignment_informations()->where('id', auth()->id() )->count() )
        {
            return [
                'status' => 400,
                'message' => 'این ملک قبلا توسط شما اعلام واگذاری شده است'
            ];
        }

        $estate->assignment_informations()->attach( auth()->user()->id );

        $estate->increment('assignment_count');

        return [
            'status' => 200,
            'message' => 'اعلام واگذاری شما با موفقیت ثبت شد'
        ];
    }
}