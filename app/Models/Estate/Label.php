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

class Label extends Model implements AuditableContract
{
    use SoftDeletes, Auditable, CreatorRelationship;
    use Translatable, CreateTimeline, ModelHelpersTrait;
    use SearchableTrait, Filterable;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'color',
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
            'label_translations.title' => 10,
            'label_translations.description' => 5,
        ],
        'joins' => [
            'label_translations' => ['labels.id', 'label_translations.label_id'],
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'color',
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
     * Get all the estates with the certain label
     */
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }
}
