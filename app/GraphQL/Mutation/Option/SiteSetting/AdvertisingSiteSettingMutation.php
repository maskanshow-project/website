<?php

namespace App\GraphQL\Mutation\Option\SiteSetting;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use GraphQL\Type\Definition\ResolveInfo;
use Spatie\MediaLibrary\Models\Media;

class AdvertisingSiteSettingMutation extends BaseSiteSettingMutation
{
    public $field = 'ads';

    public function args()
    {
        return [
            $this->field => [
                'type' => Type::listOf(\GraphQL::type('slider_item_input'))
            ],
        ];
    }

    public function resolve($root, $args, SelectFields $fields, ResolveInfo $info)
    {
        $args = collect($args);

        if (!$args->get($this->field))
            return $this->result(false);

        $this->updateSlider($args);

        return $this->result();
    }

    public function result($status = true)
    {
        return [
            'status' => $status ? 200 : 400,
            'message' => $status ? 'تبلیغات وبسایت با موفقیت بروزرسانی شد' : 'متاسفانه تبلیغات وبسایت هیچ تغییری نکرد'
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

            $image = null;

            if ($slider_item[$index] ?? false) {
                if ($slide['delete_image'] ?? false)
                    if ($slider_item[$index]['image'] ?? false)
                        Media::where('id', $slider_item[$index]['image'])->delete();

                if ($slide['image'] ?? false) {
                    if ($slider_item[$index]['image'] ?? false)
                        Media::where('id', $slider_item[$index]['image'])->delete();

                    $image = $slider->addMedia($slide['image'])->toMediaCollection();
                }

                $slider_item[$index] = [
                    'link' => $slide['link'] ?? $slider_item[$index]['link'] ?? null,
                    'image' => $image->id ?? $slider_item[$index]['image'] ?? null
                ];
            } else {
                if ($slide['image'] ?? false)
                    $image = $slider->addMedia($slide['image'])->toMediaCollection();

                $slider_item[$index] = [
                    'link' => $slide['link'] ?? null,
                    'image' => $image->id ?? null
                ];
            }
        }

        $slider->update(['value' => json_encode($slider_item->take(count($args->get($this->field))), true)]);
    }
}
