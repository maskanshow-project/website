<?php

namespace App\Models\Places;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use App\Helpers\CreateTimeline;
use App\Helpers\CreatorRelationship;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use App\Helpers\ModelHelpersTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Models\User\Office;
use EloquentFilter\Filterable;

class Area extends Model implements AuditableContract
{
    use SoftDeletes, Auditable, SpatialTrait;
    use CreateTimeline, CreatorRelationship, SoftCascadeTrait;
    use SearchableTrait, Filterable, ModelHelpersTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The relations that must have soft deleted with with model.
     *
     * @var array
     */
    protected $softCascade = [
        'streets'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id', 
        'name',
        'coordinates'
    ];

    /**
     * The attributes that store as spatial field in storage.
     *
     * @var array
     */
    protected $spatialFields = [
        'coordinates',
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
            'name' => 10,
        ],
    ];
    
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'is_active'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /****************************************
     **            Relationships
     ***************************************/
    
    /**
     * Get the province that owns the city.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get all of the users for the city.
     */
    public function users()
    {
        return $this->hasMany(\App\User::class);
    }

    /**
     * Get all of the streets for the area.
     */
    public function streets()
    {
        return $this->hasMany(Street::class);
    }

    /**
     * Get all of the offices that located in the area
     */
    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}
