<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;

class OfficeRequest extends MainRequest
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
            'name'           => 'required|string|max:50',
            'username'       => [
                $this->requiredOrFilled(),
                'string',
                'min:6',
                'max:30',
                Rule::unique('offices')->ignore( $args['id'] ?? null )
            ],
            'description'    => 'nullable|string|max:255',
            'address'        => [$this->requiredOrFilled(), 'string', 'max:100'],
            'phone_number'   => [
                $this->requiredOrFilled(),
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                Rule::unique('offices')->ignore( $args['id'] ?? null )
            ],
        ];
    }
}
