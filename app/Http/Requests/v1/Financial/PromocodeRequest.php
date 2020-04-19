<?php

namespace App\Http\Requests\v1\Financial;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTranslation;
use Illuminate\Validation\Rule;

class PromocodeRequest extends MainRequest
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
            'code'                  => [
                $this->requiredOrFilled(),
                'string',
                'max:30',
                Rule::unique('promocodes')
                    ->ignore( $args['id'] ?? null )
                    ->where(function ($query) use($args) {
                        return $query->where('deleted_at', null);
                    }),
            ],
            'title'                 => [
                $this->requiredOrFilled(),
                'string',
                'max:50',
                new UniqueTranslation('promocodes', $args['id'] ?? null)
            ],
            'description'           => 'nullable|string|max:250',
            'cost'                  => [$this->requiredOrFilled(), 'integer', 'min:1', 'max:100'],
            'quantity'              => 'nullable|integer|digits_between:1,6',
            'expired_at'            => ['nullable', 'date', 'date_format:Y-m-d H:i:s', 'after:' . now()],

            /* relateion */
            'plans'                 => 'nullable|array',
            'plans.*'               => 'required|integer|exists:plans,id',
        ];
    }
}
