<?php

namespace App\Http\Requests\v1\Place;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;

class StreetRequest extends MainRequest
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
                Rule::unique('streets')
                    ->ignore( $args['id'] ?? null )
                    ->where(function ($query) use($args) {
                        return $query->where('area_id', $args['area_id'] ?? null);
                    }),
            ],
            'lat'       => [ $this->requiredOrFilled(), 'numeric' ],
            'lng'       => [ $this->requiredOrFilled(), 'numeric' ],

            /* relateion */
            'area_id'   => [$this->requiredOrFilled(), 'integer', 'exists:areas,id'],
        ];
    }
}