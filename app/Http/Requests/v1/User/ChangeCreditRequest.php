<?php

namespace App\Http\Requests\v1\User;

use App\Http\Requests\v1\MainRequest;

class ChangeCreditRequest extends MainRequest
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
            'count'         => 'required|integer|max:100000',
            'registered'    => 'required|integer|max:10000',
            'days'          => 'required|integer|max:1000',
            'type'          => 'required|boolean',
        ];
    }
}
