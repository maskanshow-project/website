<?php

namespace App\Models\Estate;

use Illuminate\Database\Eloquent\Model;

class EstateTranslation extends Model
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
        'description',
        'address',
        'plaque',
        'advantages',
        'disadvantages',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'advantages' => 'array',
        'disadvantages' => 'array',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
