<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\v1\MainRequest;

class ResetPasswordRequest extends MainRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($args, $method)
    {
        $this->method = $method;

        if ( $method === 'REQUEST' )
        {
            return [
                'email'         => 'required|string|max:100',
            ];
        }

        return [
            'token'             => 'required|string|size:100',
            'password'          => 'required|min:6|confirmed',
        ];
    }
}
