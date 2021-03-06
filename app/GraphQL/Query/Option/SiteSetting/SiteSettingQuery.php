<?php

namespace App\GraphQL\Query\Option\SiteSetting;

use App\GraphQL\Query\MainQuery;
use App\Models\Option\SiteSetting;
use Closure;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;

class SiteSettingQuery extends MainQuery
{
    public function type(): \GraphQL\Type\Definition\Type
    {
        return \GraphQL::type('site_settings');
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

        $options = SiteSetting::select('id', 'name')
            ->whereIn('name', $fields->getSelect())
            ->with('media:id,model_type,model_id,file_name')
            ->get();


        return $options->whereIn('name', [
            'title', 'description', 'phone', 'address', 'theme_color', 'banner_link', 'is_enabled'
        ])
            ->pluck('value', 'name')
            ->merge(
                $options->whereIn('name', ['banner', 'header_banner'])
                    ->pluck('media', 'name')
            )
            ->merge(
                $options->whereIn('name', ['opinions', 'posters', 'ads'])
                    ->keyBy('name')
            );
    }
}
