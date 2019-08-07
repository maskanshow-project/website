<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Spec\SpecRow;

class CheckRequiredSpecification implements Rule
{
    public $field;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ( $value['is_required'] ?? false )
        {
            if ( isset( $value['data'] ) && ( $value || $value == 0 ) )
                return true;
                
            if ( $value['value'] ?? false)
                return true;
                
            if ( $value['values'] ?? false)
                return true;
    

            $this->field = SpecRow::select('id')->find( $value['id'] )->title ?? '';

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "لطفا فیلد {$this->field} در مشخصات ملک را وارد کنید";
    }
}
