<?php

namespace App\Http\Requests\v1\Spec;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTranslation;
use Illuminate\Validation\Rule;

class SpecificationRequest extends MainRequest
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
                new UniqueTranslation('specs', $args['id'] ?? null)
            ],
            'description'       => 'nullable|string|max:255',
            'is_active'         => 'nullable|boolean',
            
            /**
             * relateion 
             */
            'estate_type_id'    => [
                'required',
                'integer',
                'exists:estate_types,id',
                Rule::unique('specs')->ignore($args['id'] ?? null),
            ],
        ];
    }
}
