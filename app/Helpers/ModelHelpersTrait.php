<?php

namespace App\Helpers;

use App\User;

trait ModelHelpersTrait
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if ( self::$create_uuid ?? false )
                $model->id = substr(md5( time() . rand() ), 0, 12);


            if ( self::$has_user ?? true )
            {
                if ( app()->runningInConsole() )
                    $model->user_id = User::first()->id ?? null;
                
                else 
                    $model->user_id = auth()->user()->id ?? null;
            }

            if ( self::$jalali_time ?? true )
                $model->jalali_created_at = jdate();
        });
    }
}