<?php

namespace App\Http\Requests\v1\Financial;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTranslation;
use Illuminate\Validation\Rule;

class PlanRequest extends MainRequest
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
            'color'                 => [
                'nullable',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
                Rule::unique('plans')
                    ->ignore( $args['id'] ?? null )
                    ->where(function ($query) use($args) {
                        return $query->where('deleted_at', null);
                    }),
            ],
            'title'                 => [
                $this->requiredOrFilled(),
                'string',
                'max:50',
                new UniqueTranslation('plans', $args['id'] ?? null)
            ],
            'description'           => 'nullable|string|max:250',
            'price'                 => [$this->requiredOrFilled(), 'integer', 'digits_between:1,10'],
            'visited_estate_count'  => [$this->requiredOrFilled(), 'integer', 'digits_between:1,6'],
            'credit_days_count'     => [$this->requiredOrFilled(), 'integer', 'digits_between:1,4'],
            'registered_estate_count'  => [$this->requiredOrFilled(), 'integer', 'digits_between:1,6'],
        ];
    }
}
