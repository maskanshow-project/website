<?php

namespace App\GraphQL\Mutation\Option\SiteSetting;

use Closure;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\UploadType;

class InfoSiteSettingMutation extends BaseSiteSettingMutation
{
    public function args(): array
    {
        return [
            'title' => [
                'type' => Type::string()
            ],
            'description' => [
                'type' => Type::string()
            ],
            'phone' => [
                'type' => Type::string()
            ],
            'address' => [
                'type' => Type::string()
            ],
            'logo' => [
                'type' => \GraphQL::type('Upload')
            ],
            'watermark' => [
                'type' => \GraphQL::type('Upload')
            ],
            'banner' => [
                'type' => \GraphQL::type('Upload')
            ],
            'header_banner' => [
                'type' => \GraphQL::type('Upload')
            ],
            'banner_link' => [
                'type' => Type::string()
            ],
            'theme_color' => [
                'type' => Type::string()
            ],
            'is_enabled' => [
                'type' => Type::boolean()
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $args = collect($args);

        if ($args->isEmpty())
            return $this->result(false);

        $this->updateInfoFields($args);
        $this->updateImageFields($args);

        return $this->result();
    }

    public function result($status = true)
    {
        return [
            'status' => $status ? 200 : 400,
            'message' => $status ? 'کلیه تنظیمات با موفقیت بروزرسانی شد' : 'متاسفانه تنظیمات هیچ تغییری نکرد'
        ];
    }

    public function updateInfoFields($args)
    {
        foreach ($args->only(
            'title',
            'description',
            'phone',
            'address',
            'theme_color',
            'banner_link',
            'is_enabled'
        ) as $option => $value) {
            $model = $this->model::whereName($option)->first();

            if ($model)
                $model->update(['value' => $value]);

            else
                $this->model::create(['name' => $option, 'value' => $value]);
        }
    }

    public function updateImageFields($args)
    {
        foreach ($args->only('logo', 'watermark', 'banner', 'header_banner') as $option => $value) {
            if (!$value) continue;

            $model = $this->model::whereName($option)->first();

            if ($model) {
                $model->clearMediaCollection();
                $model->addMedia($value)->toMediaCollection();
            } else
                $this->model::create(['name' => $option])->addMedia($value)->toMediaCollection();
        }
    }
}
