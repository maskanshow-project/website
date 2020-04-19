<?php

namespace App\Http\Requests\User\v1;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTranslation;

class MessageRequest extends MainRequest
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
            'title'         => [
                $this->requiredOrFilled(),
                'string',
                'max:100',
            ],
            'message'       => [
                $this->requiredOrFilled(),
                'string',
                'max:2000'
            ],
            'type'          => [
                $this->requiredOrFilled(),
                'string',
                'in:primary,info,success,danger,warning,default'
            ],
            'expired_at'    => [
                $this->requiredOrFilled(),
                'date',
                'date_format:Y-m-d H:i:s',
                'after:' . now()
            ],
            'is_active'     => 'nullable|boolean',

            /* relateion */
            'role_id'       => [$this->requiredOrFilled(), 'integer', 'exists:roles,id'],
        ];
    }
}
