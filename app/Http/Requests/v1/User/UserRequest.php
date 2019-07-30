<?php

namespace App\Http\Requests\User\v1;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;

class UserRequest extends MainRequest
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
                $this->requiredOrFilled(),
                'string',
                'min:6',
                'max:30',
                'alpha_dash',
                Rule::unique('users')->ignore( $args['id'] ?? null )
            ],
            'email'             => [
                $this->requiredOrFilled(),
                'email',
                Rule::unique('users')->ignore( $args['id'] ?? null )
            ],
            'phone_number'  => [
                $this->requiredOrFilled(),
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                Rule::unique('users')->ignore( $args['id'] ?? null )
            ],
            'address'           => 'nullable|string|max:100',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'national_code'     => 'nullable|size:10',
            'gender'            => 'nullable|boolean',
            
            // relations
            'city_id'           => 'nullable|integer|exists:cities,id',
            'area_id'           => 'nullable|integer|exists:areas,id',

            'permissions'       => 'nullable|array',
            'permissions.*'     => 'required|integer|exists:permissions,id',
            
            'roles'             => 'nullable|array|exists:roles,id',
            'roles.*'           => 'required|integer',
        ];
    }
}
