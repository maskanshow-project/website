<?php

namespace App\GraphQL\Mutation\User\Favorite;

use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\Estate\Estate;
use Closure;

class AddFavoriteMutation extends BaseFavoriteMutation
{
    /**
     * Add a estate to shopping cart
     *
     * @param estate $estate
     * @return void
     */
    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $estate = Estate::findOrFail($args['estate']);

        auth()->user()->favorites()->syncWithoutDetaching($estate->id);

        return [
            'status' => 200,
            'message' => $estate->title . 'با موفقیت به لیست علاقه مندی ها اضافه شد .'
        ];
    }
}
