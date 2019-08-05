<?php

namespace App\Http\Requests\v1;

class LoginRequest extends MainRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [ 'required', 'alpha_dash' ], 
            'password' => 'required', 
        ];
    }
}
