<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use EloquentFilter\Filterable;
use App\Helpers\CreateTimeline;
use App\Helpers\CreatorRelationship;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Helpers\ModelHelpersTrait;
use App\User;
use App\Models\Places\Area;

class Office extends Model implements AuditableContract
{
    use SoftDeletes, Auditable, Filterable;
    use CreateTimeline, CreatorRelationship, ModelHelpersTrait, SearchableTrait;

    protected static $has_user = false;

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
        'username',
        'description',
        'address',
        'phone_number'
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
            'offices.name' => 10,
            'offices.address' => 3,
            'offices.phone_number' => 5,
            'offices.username' => 6,

            'users.first_name' => 9,
            'users.last_name' => 9,
            'users.username' => 7,
        ],
        'joins' => [
            'users' => ['users.id','offices.user_id'],
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'area_id',
        'name',
        'description',
        'address',
        'phone_number'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /****************************************
     **             Relations
     ****************************************/

    /**
     * Get the area of the office
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the owner of the office
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all the employees of the office
     */
    public function employees()
    {
        return $this->hasMany(User::class);
    }
}
