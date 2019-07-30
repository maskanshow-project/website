<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniqueTranslation implements Rule
{
    private $table;

    private $id;

    private $singular;

    private $field;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $id = null, $field = null)
    {
        $this->table = $table;
        $this->id = $id;
        $this->singular = Str::singular( $table );
        $this->field = $field;
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
        $result = DB::table( $this->table );

        $result->join("{$this->singular}_translations", "{$this->table}.id", '=', "{$this->singular}_translations.{$this->singular}_id");
        
        if ( $this->id )
            $result->where("{$this->table}.id", '!=', $this->id);

        $result->where($this->field ?? $attribute, $value)->where("{$this->table}.deleted_at", null);

        return $result->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique');
    }
}
