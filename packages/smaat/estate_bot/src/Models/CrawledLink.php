<?php

namespace SmaaT\EstateBot\Models;

use App\Models\Estate\Estate;
use Illuminate\Database\Eloquent\Model;

class CrawledLink extends Model
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
        'estate_id',
        'provider',
        'link',
        'errors',
        'crawled_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['crawled_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'errors'    => 'array'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /****************************************
     **             Relations
     ***************************************/

    /**
     * Get the estate of crawled link
     */
    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }


    /****************************************
     **              Methods
     ***************************************/
}
