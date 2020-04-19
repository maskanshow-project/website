<?php

namespace App\Http\Requests\v1\Estate;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;
use App\Rules\UniqueTranslation;

class LabelRequest extends MainRequest
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
            'title'                 => [
                $this->requiredOrFilled(),
                'string',
                'max:50',
                new UniqueTranslation('labels', $args['id'] ?? null)
            ],
            'description'           => 'nullable|string|max:250',
            'color'                 => [
                'nullable',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
                Rule::unique('labels')
                    ->ignore( $args['id'] ?? null )
                    ->where(function ($query) use($args) {
                        return $query->where('deleted_at', null);
                    }),
            ],
        ];
    }
}
