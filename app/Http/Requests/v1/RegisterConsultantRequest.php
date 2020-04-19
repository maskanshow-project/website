<?php

namespace App\Http\Requests\v1;

use Illuminate\Validation\Rule;

class RegisterConsultantRequest extends MainRequest
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
            // User info
            'first_name'        => 'nullable|string|max:20',
            'first_name'        => 'nullable|string|max:30',
            'username'          => [
                'required',
                'string',
                'alpha_dash',
                'min:6',
                'max:30',
                'alpha_dash',
                Rule::unique('users')->where(function ($query) use ($args) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'email'             => [
                'nullable',
                'email',
                Rule::unique('users')->where(function ($query) use ($args) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'password'          => 'required|confirmed',
            'phone_number'      => [
                'required',
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                Rule::unique('users')->where(function ($query) use ($args) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'address'           => 'nullable|string|max:100',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'national_code'     => 'nullable|size:10',
            'gender'            => 'nullable|boolean',

            // Office info
            'office_name'           => 'required|string|max:50',
            'office_username'       => [
                'required',
                'alpha_dash',
                'string',
                'min:6',
                'max:30',
                Rule::unique('offices', 'username')->where(function ($query) use ($args) {
                    return $query->where('deleted_at', null);
                }),
            ],
            'office_description'    => 'nullable|string|max:255',
            'office_address'        => 'required|string|max:100',
            'office_phone_number'   => [
                'required',
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                Rule::unique('offices', 'phone_number')->where(function ($query) use ($args) {
                    return $query->where('deleted_at', null);
                }),
            ],

            // relations
            'city_id'           => 'nullable|integer|exists:cities,id',
            'area_id'           => 'required|integer|exists:areas,id',
            'reagent_code'      => [
                'required',
                'string',
                Rule::exists('users', 'username')->where(function ($query) {
                    $query->where('can_has_member', 1);
                }),
            ],
        ];
    }
}
