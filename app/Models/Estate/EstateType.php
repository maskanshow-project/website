<?php

namespace App\Models\Estate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Helpers\CreatorRelationship;
use Astrotomic\Translatable\Translatable;
use App\Helpers\CreateTimeline;
use App\Helpers\ModelHelpersTrait;
use App\Models\Spec\Spec;
use Nicolaslopezj\Searchable\SearchableTrait;
use EloquentFilter\Filterable;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class EstateType extends Model implements AuditableContract
{
    use SoftDeletes, Auditable, CreatorRelationship;
    use Translatable, CreateTimeline, ModelHelpersTrait;
    use SearchableTrait, Filterable, SoftCascadeTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The relations that must have soft deleted with with model.
     *
     * @var array
     */
    protected $softCascade = [
        'estates'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'icon',
        'is_active'
    ];

    /**
     * The attributes that are store in the transltion model.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'similar_titles',
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
            'estate_type_translations.title' => 10,
            'estate_type_translations.description' => 5,
        ],
        'joins' => [
            'estate_type_translations' => ['estate_types.id', 'estate_type_translations.estate_type_id'],
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'icon',
        'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active'             => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];


    /****************************************
     **             Relations
     ***************************************/

    /**
     * Relation to Product model
     *
     * @return Product Model
     */
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }

    /**
     * get the specification table of the estate type
     */
    public function spec()
    {
        return $this->hasOne(Spec::class);
    }

    /**
     * Get all the assignments of the type
     */
    public function assignments()
    {
        return $this->belongsToMany(Assignment::class, 'assignment_type');
    }

    /**
     * Get all the features of the estate type
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'estate_type_feature');
    }
}
