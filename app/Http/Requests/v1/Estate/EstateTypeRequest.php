<?php

namespace App\Http\Requests\v1\Estate;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTranslation;

class EstateTypeRequest extends MainRequest
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
                new UniqueTranslation('estate_types', $args['id'] ?? null)
            ],
            'description'           => 'nullable|string|max:250',
            'icon'                  => 'nullable|string|max:50',
        ];
    }
}
