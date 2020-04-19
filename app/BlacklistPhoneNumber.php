<?php

namespace App;

use App\CachableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Helpers\CreateTimeline;
use App\Helpers\CreatorRelationship;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Helpers\ModelHelpersTrait;

class BlacklistPhoneNumber extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;
    use CreateTimeline, CreatorRelationship;
    use SearchableTrait, ModelHelpersTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number',
        'description'
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
            'phone_number' => 10,
            'description' => 5,
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'phone_number',
        'description'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /****************************************
     **             Relations
     ***************************************/
    

    /****************************************
     **              Methods
     ***************************************/
}