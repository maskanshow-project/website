<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use EloquentFilter\Filterable;
use App\Helpers\CreateTimeline;
use App\Helpers\CreatorRelationship;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Astrotomic\Translatable\Translatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Helpers\ModelHelpersTrait;
use App\User;

class Promocode extends Model implements AuditableContract
{
    use SoftDeletes, Auditable, Filterable;
    use CreateTimeline, CreatorRelationship, ModelHelpersTrait;
    use SoftCascadeTrait, Translatable, SearchableTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The relations that must have soft deleted with with model.
     *
     * @var array
     */
    protected $softCascade = [
        // 'headers',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'cost',
        'quantity',
        'expired_at',
        'is_active',
    ];

    /**
     * The attributes that are store in the transltion model.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'description',
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
            'promocode_translations.title' => 10,
            'promocode_translations.description' => 5,
        ],
        'joins' => [
            'promocode_translations' => ['promocodes.id', 'promocode_translations.promocode_id']
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'title',
        'description',
        'cost',
        'count',
        'remainig_count',
        'expired_at',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
        'deleted_at'
    ];

    /****************************************
     **             Relations
     ****************************************/

    /**
     * Get all the plans of this promocodes
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'promocode_plan');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'promocode_user');
    }
}
