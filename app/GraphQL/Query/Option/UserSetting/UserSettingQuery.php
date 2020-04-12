<?php

namespace App\GraphQL\Query\Option\UserSetting;

use App\GraphQL\Query\MainQuery;
use Rebing\GraphQL\Support\SselectFields;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\Option\UserSetting;
use Closure;

class UserSettingQuery extends MainQuery
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('user_settings');
    }

    public function args(): array
    {
        return [
            // 
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $fields = $getSelectFields();

        return UserSetting::select('id', 'name')
            ->whereIn('name', $fields->getSelect())
            ->get()
            ->pluck('value', 'name');
    }
}
