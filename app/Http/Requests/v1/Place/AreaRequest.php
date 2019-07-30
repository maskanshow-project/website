<?php

namespace App\Http\Requests\v1\Place;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;

class AreaRequest extends MainRequest
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
            'name'      => [
                $this->requiredOrFilled(),
                'string',
                'max:30',
                Rule::unique('areas')
                    ->ignore( $args['id'] ?? null )
                    ->where(function ($query) use($args) {
                        return $query->where('city_id', $args['city_id'] ?? null);
                    }),
            ],
            'lat'       => [ $this->requiredOrFilled(), 'numeric' ],
            'lng'       => [ $this->requiredOrFilled(), 'numeric' ],

            /* relateion */
            'city_id'   => [$this->requiredOrFilled(), 'integer', 'exists:cities,id'],
        ];
    }
}