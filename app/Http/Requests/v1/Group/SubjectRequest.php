<?php

namespace App\Http\Requests\v1\Group;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTenant;
use App\Rules\ExistsTenant;
use App\Rules\UniqueTranslation;

class SubjectRequest extends MainRequest
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
                new UniqueTranslation('subjects', $args['id'] ?? null)
            ],
            'description'       => 'nullable|string|max:255',
            'logo'              => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
            'icon'              => 'nullable|string|max:50',
            'is_active'         => 'nullable|boolean',

            /* relateion */
            'parent_id'         => 'nullable|integer|exists:subjects,id',

            'tags'              => 'nullable|array',
            'tags.*'            => 'required|string|max:100',
        ];
    }
}