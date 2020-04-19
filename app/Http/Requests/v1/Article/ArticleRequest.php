<?php

namespace App\Http\Requests\v1\Article;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTenant;
use App\Rules\ExistsTenant;
use Illuminate\Validation\Rule;
use App\Rules\UniqueTranslation;

class ArticleRequest extends MainRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($args, $method)
    {
        $this->method = $method;

        $rules = [
            'title'         => [
                $this->requiredOrFilled(),
                'string',
                'max:100',
                new UniqueTranslation('articles', $args['id'] ?? null)
            ],
            'description'   => 'nullable|string|max:255',
            'body'          => [$this->requiredOrFilled(), 'string'],
            'image'         => [
                $method === 'CREATE' ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif',
                'max:1024'
            ],
            'reading_time'  => 'nullable|digits_between:1,2',
            'is_active'     => 'nullable|boolean',

            /* relateion */
            'subjects'      => 'nullable|array',
            'subjects.*'    => 'required|integer|exists:subjects,id',

            'keywords'      => 'nullable|array',
            'keywords.*'    => 'required|string|max:100',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'body' => 'متن مقاله',
            'image' => 'تصویر مقاله'
        ];
    }
}
