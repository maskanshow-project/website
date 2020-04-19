<?php

namespace App\Http\Requests\v1\Estate;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;
use App\Rules\UniqueTranslation;

class AssignmentRequest extends MainRequest
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
            'title'                     => [
                $this->requiredOrFilled(),
                'string',
                'max:50',
                new UniqueTranslation('assignments', $args['id'] ?? null)
            ],
            'similar_titles'            => 'nullable|array',
            'similar_titles.*'          => 'required|string|max:50',
            'description'               => 'nullable|string|max:250',
            'icon'                      => 'nullable|string|max:50',
        ];
    }
}
