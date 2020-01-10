<?php

namespace App\Http\Requests\v1\Option;

use App\Http\Requests\v1\MainRequest;

class SiteSettingRequest extends MainRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($args, $method)
    {
        $this->method = $method;

        return [
            'title'                 => 'filled|string|max:50',
            'description'           => 'nullable|string|max:250',
            'phone'                 => [
                'nullable',
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
            ],
            'address'               => 'nullable|string|max:250',
            'theme_color'           => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'is_active'             => 'nullable|boolean',

            'banner'                => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
            'header_banner'         => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',

            'opinions'              => 'nullable|array',
            'opinions.*'            => 'required|array',
            'opinions.*.avatar'     => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
            'opinions.*.post'       => 'nullable|string|max:50',
            'opinions.*.full_name'  => 'nullable|string|max:100',
            'opinions.*.opinion'    => 'nullable|string|max:200',

            'posters'               => 'nullable|array',
            'posters.*'             => 'required|array',
            'posters.*.image'       => 'nullable|image|mimes:jpeg,jpg,png,gif|max:3072',
            'posters.*.title'       => 'nullable|string|max:50',
            'posters.*.description' => 'nullable|string|max:100',
            'posters.*.link'        => 'nullable|url|max:200',

            'ads'                   => 'nullable|array',
            'ads.*'                 => 'required|array',
            'ads.*.image'           => 'nullable|image|mimes:jpeg,jpg,png,gif|max:3072',
            'ads.*.link'            => 'nullable|max:200',
        ];
    }
}
