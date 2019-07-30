<?php

namespace App\Http\Requests\v1\Financial;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTranslation;
use Illuminate\Validation\Rule;

class PaymentRequest extends MainRequest
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
            'plan'              => 'required|integer|exists:plans,id',
            'promocode'         => [
                'nullable',
                'string',
                Rule::exists('promocodes', 'code')->where(function ($query) {
                    $query
                        ->where(function ($query) {
                            $query->where('expired_at', '>', now() )->orWhere('expired_at', null);
                        })
                        ->where(function ($query) {
                            $query->where('quantity', '>=', '1')->orWhere('quantity', null);
                        });
                }),
            ],
            'description'       => 'nullable|string|max:250',
        ];
    }
}
