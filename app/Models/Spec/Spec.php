<?php

namespace App\Models\Spec;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group\Category;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Models\Product\Product;
use EloquentFilter\Filterable;
use App\Helpers\CreateTimeline;
use App\Helpers\CreatorRelationship;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Dimsav\Translatable\Translatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Helpers\ModelHelpersTrait;
use App\Models\Estate\EstateType;

class Spec extends Model implements AuditableContract
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
        'headers',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'estate_type_id',
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
            'spec_translations.title' => 10,
            'spec_translations.description' => 5,
        ],
        'joins' => [
            'spec_translations' => ['specs.id','spec_translations.spec_id']
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
        'is_active'
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
    protected $dates = ['deleted_at'];

    /****************************************
     **             Relations
     ****************************************/

    /**
     * Get all the spec header of the spec.
     */
    public function estate_type()
    {
        return $this->belongsTo(EstateType::class);
    }

    /**
     * Get all the spec header of the spec.
     */
    public function headers()
    {
        return $this->hasMany(SpecHeader::class);
    }

    /**
     * Get all the spec header of the spec.
     */
    public function filters()
    {
        return $this->hasManyThrough(SpecRow::class, SpecHeader::class);
    }

    /**
     * Get all the products of the spec.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function compare()
    {
        if ( !session('compare_table') || !session('compare') ) return null;

        return Static::find( session('compare_table') )
            ->load([
                'specHeaders:id,spec_id,title,description',
                'specHeaders.specRows:id,spec_header_id,title,label,values,help,multiple',
                'specHeaders.specRows.specDatas' => function ($query) {
                    $query->whereIn('product_id', session( 'compare' ) );
                }
            ]);
    }
}
