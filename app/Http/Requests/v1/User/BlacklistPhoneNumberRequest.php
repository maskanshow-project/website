<?php

namespace App\Http\Requests\User\v1;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;

class BlacklistPhoneNumberRequest extends MainRequest
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
            'phone_number'  => [
                'required',
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                Rule::unique('blacklist_phone_numbers')->ignore( $args['id'] ?? null )
            ],
            'description'   => 'nullable|string|max:255',
        ];
    }
}
