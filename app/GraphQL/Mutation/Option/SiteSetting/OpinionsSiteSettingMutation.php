<?php

namespace App\GraphQL\Mutation\Option\SiteSetting;

use Closure;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use Spatie\MediaLibrary\Models\Media;

class OpinionsSiteSettingMutation extends BaseSiteSettingMutation
{
    public $field = 'opinions';

    public function args(): array
    {
        return [
            $this->field => [
                'type' => Type::listOf(\GraphQL::type('customer_opinion_input'))
            ],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $args = collect($args);

        if (is_array($args->get($this->field)) && count($args->get($this->field)) === 0) {
            $slider = $this->model::whereName($this->field)->first();
            $slider->update(['value' => '[]']);

            return $this->result();
        }

        if (!$args->get($this->field))
            return $this->result(false);

        $this->updateSlider($args);

        return $this->result();
    }

    public function result($status = true)
    {
        return [
            'status' => $status ? 200 : 400,
            'message' => $status ? 'نظرات وبسایت با موفقیت بروزرسانی شد' : 'متاسفانه نظرات وبسایت هیچ تغییری نکرد'
        ];
    }

    public function updateSlider($args)
    {
        $slider = $this->model::whereName($this->field)->first();

        if (!$slider)
            $slider = $this->model::create(['name' => $this->field]);

        $slider_item = $slider->value ? collect(json_decode($slider->value, true)) : collect([]);

        foreach ($args->get($this->field) as $index => $slide) {
            if (!$slide) continue;

            $avatar = null;

            if ($slider_item[$index] ?? false) {
                if ($slide['avatar'] ?? false) {
                    if ($slider_item[$index]['avatar'] ?? false)
                        Media::where('id', $slider_item[$index]['avatar'])->delete();

                    $avatar = $slider->addMedia($slide['avatar'])->toMediaCollection();
                }

                $slider_item[$index] = [
                    'post' => $slide['post'] ?? $slider_item[$index]['post'] ?? null,
                    'fullname' => $slide['fullname'] ?? $slider_item[$index]['fullname'] ?? null,
                    'opinion' => $slide['opinion'] ?? $slider_item[$index]['opinion'] ?? null,
                    'avatar' => $avatar->id ?? $slider_item[$index]['avatar'] ?? null
                ];
            } else {
                if ($slide['avatar'] ?? false)
                    $avatar = $slider->addMedia($slide['avatar'])->toMediaCollection();

                $slider_item[$index] = [
                    'post' => $slide['post'] ?? null,
                    'fullname' => $slide['fullname'] ?? null,
                    'opinion' => $slide['opinion'] ?? null,
                    'avatar' => $avatar->id ?? null
                ];
            }
        }

        $slider->update([
            'value' => json_encode($slider_item->take(count($args->get($this->field))), true)
        ]);
    }
}
