<?php

namespace App\Models\Places;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use App\Helpers\CreateTimeline;
use App\Helpers\CreatorRelationship;
use App\Helpers\ModelHelpersTrait;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Models\Estate\Estate;
use EloquentFilter\Filterable;

class Street extends Model implements AuditableContract
{
    use SoftDeletes, Auditable, SpatialTrait;
    use CreateTimeline, CreatorRelationship;
    use SearchableTrait, Filterable, ModelHelpersTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'area_id', 
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
     * Get the area of the street
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get all the estates of the street.
     */
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }
}
