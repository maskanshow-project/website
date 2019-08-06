<?php

namespace App\Http\Requests\v1;

use Illuminate\Validation\Rule;

class RegisterRequest extends MainRequest
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
            'first_name'        => 'nullable|string|max:20',
            'first_name'        => 'nullable|string|max:30',
            'username'          => [
                'required',
                'string',
                'alpha_dash',
                'min:6',
                'max:30',
                'alpha_dash',
                Rule::unique('users')->where(function ($query) use($args) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'email'             => [
                'nullable',
                'email',
                Rule::unique('users')->where(function ($query) use($args) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'password'          => 'required|min:6|confirmed',
            'phone_number'      => [
                'required',
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                Rule::unique('users')->where(function ($query) use($args) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'address'           => 'nullable|string|max:100',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'national_code'     => 'nullable|size:10',
            'gender'            => 'nullable|boolean',
            
            // relations
            'city_id'           => 'nullable|integer|exists:cities,id',
            'area_id'           => 'nullable|integer|exists:areas,id',
        ];
    }
}
