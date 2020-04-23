<?php

namespace SmaaT\EstateBot\Models;

use App\Models\Estate\Estate;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class CrawledLink extends Model
{
    use SearchableTrait;

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
     * Searchable rules.
     *
     * Columns and their priority in search results.
     * Columns with higher values are more important.
     * Columns with equal values have equal importance.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'crawled_links.estate_id' => 10,
            'crawled_links.link' => 2,
            'estates.landlord_fullname' => 8,
            'estates.landlord_phone_number' => 8,
            // 'estates.address' => 5,
        ],
        'joins' => [
            'estates' => ['crawled_links.estate_id', 'estates.id']
        ],
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
