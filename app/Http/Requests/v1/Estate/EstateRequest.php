<?php

namespace App\Http\Requests\v1\Estate;

use App\Http\Requests\v1\MainRequest;
use Illuminate\Validation\Rule;
use App\Rules\UniqueTranslation;
use App\Rules\PlaqueNotInAddress;
use App\Rules\Blacklist;
use App\Rules\CheckRequiredSpecification;

class EstateRequest extends MainRequest
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
            'title'                 => [
                'nullable',
                'string',
                'max:50',
                new UniqueTranslation('estates', $args['id'] ?? null)
            ],
            'code'                  => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('estates')->ignore($args['id'] ?? null),
            ],
            'description'           => 'nullable|string|max:255',
            'landlord_fullname'     => [$this->requiredOrFilled(), 'string', 'max:50'],
            'landlord_phone_number' => [
                $this->requiredOrFilled(),
                'string',
                'regex:/^(\+98|0)?9\d{9}$/',
                new Blacklist
            ],
            'address'               => [
                $this->requiredOrFilled(),
                'string',
                'max:100',
                new PlaqueNotInAddress
            ],
            'plaque'                => [$this->requiredOrFilled(), 'string', 'max:20'],
            'aparat_video'          => 'nullable|url|starts_with:https://www.aparat.com/v/',
            
            'sales_price'           => 'nullable|integer|digits_between:1,10',
            'mortgage_price'        => 'nullable|integer|digits_between:1,10',
            'rental_price'          => 'nullable|integer|digits_between:1,10',
            
            'area'                  => [$this->requiredOrFilled(), 'integer', 'digits_between:1,6'],
            'advantages'            => 'nullable|array',
            'advantages.*'          => 'required|string|max:100',
            'disadvantages'         => 'nullable|array',
            'disadvantages.*'       => 'required|string|max:100',
            'tags'                  => 'nullable|array',
            'tags.*'                => 'required|string|max:100',
            'want_cooperation'      => 'nullable|boolean',

            /* relateion */
            'street_id'             => [$this->requiredOrFilled(), 'integer', 'exists:streets,id'],
            'label_id'              => 'nullable|integer|exists:labels,id',
            'assignment_id'         => [$this->requiredOrFilled(), 'integer', 'exists:assignments,id'],
            'estate_type_id'        => [$this->requiredOrFilled(), 'integer', 'exists:estate_types,id'],
            'lat'                   => 'nullable|numeric',
            'lng'                   => 'nullable|numeric',
            
            'features'              => 'nullable|array',
            'features.*'            => 'required|integer|exists:features,id',

            'specs'                 => 'nullable|array',
            'specs.*'               => [
                'required',
                'array',
                new CheckRequiredSpecification
            ],
            'specs.*.id'            => 'required|integer|exists:spec_rows,id',
            'specs.*.data'          => 'nullable|string',
            'specs.*.value'         => 'nullable|integer',
            'specs.*.values'        => 'nullable|array',
            'specs.*.values.*'      => 'required|integer|exists:spec_defaults,id',

            'photos'                => 'nullable|array',
            'photos.*'              => 'required|image|mimes:jpeg,jpg,png,gif|max:1024',

            'deleted_images'        => ['nullable', 'array'],
            'deleted_images.*'      => [
                'required',
                'integer',
                Rule::exists('media', 'id')->where(function ($query) use($args) {
                    return $query->where('model_type', 'App\Models\Estate\Estate')
                                 ->where('model_id', $args['id'] ?? false);
                })
            ],
        ];
    }
}
