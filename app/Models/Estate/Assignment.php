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
use Nicolaslopezj\Searchable\SearchableTrait;
use EloquentFilter\Filterable;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Assignment extends Model implements AuditableContract
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
        'color',
        'has_sales_price',
        'has_mortgage_price',
        'has_rental_price',
        'jalali_created_at',
        'is_active'
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
            'assignment_translations.title' => 10,
            'assignment_translations.description' => 5,
        ],
        'joins' => [
            'assignment_translations' => ['assignments.id', 'assignment_translations.assignment_id'],
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'has_sales_price',
        'has_mortgage_price',
        'has_rental_price',
        'jalali_created_at',
        'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_sales_price'       => 'boolean',
        'has_mortgage_price'    => 'boolean',
        'has_rental_price'      => 'boolean',
        'jalali_created_at'     => 'boolean',
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
     * Get all the types of the assignemnt
     */
    public function estate_types()
    {
        return $this->belongsToMany(EstateType::class, 'assignment_type');
    }
}
