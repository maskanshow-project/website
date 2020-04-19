<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniquePhoneNumber implements Rule
{
    public $method;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($method)
    {
        $this->method = $method;
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
        if ($this->method === 'UPDATE') return true;

        return DB::table('estates')
            ->where('landlord_phone_number', $value)
            ->where("created_at", '>=', now()->subDay(3))
            ->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "در سه روز گذشته ملک مشابهی با این شماره مالک ثبت شده است";
    }
}
