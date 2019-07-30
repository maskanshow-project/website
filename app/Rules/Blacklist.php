<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Blacklist implements Rule
{
    private $error_message;

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
        if ( auth()->user()->hasRole('consultant') )
            return true;

        $item = DB::table('blacklist_phone_numbers')
            ->where('phone_number', $value)
            ->where("deleted_at", null)
            ->first();

        if ( !$item ) return true;
        
        $this->error_message = $item->description;
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "شماره تلفن مالک در لیست سیاه است ، علت ثبت : " . $this->error_message;
    }
}
