<?php

namespace App\GraphQL\Mutation\User\Favorite;

use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\Estate\Estate;

class RemoveFavoriteMutation extends BaseFavoriteMutation
{
    /**
     * Remove a estate from the order
     *
     * @param estate $estate
     * @return void
     */
    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $estate = Estate::findOrFail( $args['estate'] );

        auth()->user()->favorites()->detach( $estate->id );

        return [
            'status' => 200,
            'message' => $estate->title.'با موفقیت از لیست علاقه مندی ها حذف شد .'
        ];
    }
}