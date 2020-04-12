<?php

namespace App\GraphQL\Type\Option;

use App\GraphQL\Type\BaseType;
use GraphQL\Type\Definition\Type;
use function GuzzleHttp\json_decode;

class SiteSettingType extends BaseType
{
    protected $attributes = [
        'name' => 'site_settings',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'title' => [
                'type' => Type::string(),
            ],
            'description' => [
                'type' => Type::string(),
            ],
            'phone' => [
                'type' => Type::string(),
            ],
            'address' => [
                'type' => Type::string(),
            ],
            'banner_link' => [
                'type' => Type::string(),
            ],
            // 'logo' => $this->imageField('logo'),
            // 'watermark' => $this->imageField('watermark'),
            'banner' => $this->imageField('banner'),
            'header_banner' => $this->imageField('header_banner'),
            'theme_color' => [
                'type' => Type::string(),
            ],
            'is_enabled' => [
                'type' => Type::boolean(),
                'resolve' => function ($data) {
                    return is_null($data['is_enabled'] ?? null) ? true : $data['is_enabled'];
                }
            ],
            'opinions' => [
                'type' => Type::listOf(\GraphQL::type('customer_opinion')),
                'is_relation' => false,
                'resolve' => function ($data) {
                    $opinions = json_decode($data['opinions']['value'] ?? '[]');

                    foreach ($opinions as $opinion)
                        $opinion->avatar = $data['opinions']->media->where('id', $opinion->avatar ?? null)->first();

                    return $opinions;
                }
            ],
            'posters' => [
                'type' => Type::listOf(\GraphQL::type('slider_item')),
                'is_relation' => false,
                'resolve' => function ($data) {
                    $posters = json_decode($data['posters']['value'] ?? '[]');

                    foreach ($posters as $poster)
                        $poster->image = $data['posters']->media->where('id', $poster->image ?? null)->first();

                    return $posters;
                }
            ],
            'ads' => [
                'type' => Type::listOf(\GraphQL::type('slider_item')),
                'is_relation' => false,
                'resolve' => function ($data) {
                    $posters = json_decode($data['ads']['value'] ?? '[]');

                    foreach ($posters as $poster)
                        $poster->image = $data['ads']->media->where('id', $poster->image ?? null)->first();

                    return $posters;
                }
            ],
        ];
    }

    public function imageField($field = 'logo')
    {
        return [
            'type' => \GraphQL::type('single_media'),
            'is_relation' => false,
            'resolve' => function ($data) use ($field) {
                return $data[$field][0] ?? null;
            }
        ];
    }
}
