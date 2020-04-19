<?php

namespace App\Http\Requests\User\v1;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTranslation;

class RoleRequest extends MainRequest
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
            'display_name'  => [
                'required',
                'string',
                'max:100',
                new UniqueTranslation('roles', $args['id'] ?? null)
            ],
            'description'   => 'nullable|string|max:255',
            'permissions'   => ['required', 'array'],
            'permissions.*' => 'required|integer|exists:permissions,id',
            'is_active'     => 'nullable|boolean',
        ];
    }
}
