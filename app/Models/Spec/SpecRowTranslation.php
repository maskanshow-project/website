<?php

namespace App\Models\Spec;

use Illuminate\Database\Eloquent\Model;

class SpecRowTranslation extends Model
{
    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'similar_titles',
        'prefix',
        'postfix',
        'description',
        'help',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'similar_titles'    => 'array',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
