<?php

namespace App\Models\Estate;

use Cog\Likeable\Contracts\Likeable as LikeableContract;
use Cog\Likeable\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\Tags\HasTags;
use App\Models\Spec\{SpecData, Spec};
use EloquentFilter\Filterable;
use App\Helpers\CreateTimeline;
use App\Helpers\CreatorRelationship;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Dimsav\Translatable\Translatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use App\Models\Option\SiteSetting;
use Spatie\Image\Manipulations;
use App\Helpers\ModelHelpersTrait;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use App\Models\Places\City;
use App\Role;
use App\Models\Places\Street;
use App\User;
use App\Models\User\Office;

class Estate extends Model implements AuditableContract, LikeableContract, HasMedia
{
    use SoftDeletes, Auditable, HasTags;
    use Filterable, Likeable, CreateTimeline, CreatorRelationship;
    use SoftCascadeTrait, Translatable, SearchableTrait;
    use HasMediaTrait, ModelHelpersTrait, SpatialTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The relations that must have soft deleted with with model.
     *
     * @var array
     */
    protected $softCascade = [
        'spec_data'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'assignment_id',
        'estate_type_id',
        'office_id',
        'spec_id',
        'label_id',
        'street_id',
        'role_id',
        'code',

        'landlord_fullname',
        'landlord_phone_number',

        'sales_price',
        'mortgage_price',
        'rental_price',
        'assignmented_at',

        'area',
        'aparat_video',
        'coordinates',
        'want_cooperation',
        'is_active',
        'reject_reason'
    ];

    /**
     * The attributes that are store in the transltion model.
     *
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'description',
        'address',
        'plaque',
        'advantages',
        'disadvantages',
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
            'estates.id' => 10,
            'estate_translations.title' => 6,
            'estate_translations.description' => 5,
            'estate_translations.address' => 5,
            'landlord_fullname' => 6,
            'landlord_phone_number' => 6,
        ],
        'joins' => [
            'estate_translations' => ['estates.id', 'estate_translations.estate_id'],
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'assignment_id',
        'estate_type_id',
        'spec_id',
        'label_id',
        'code',
        'area',
        'sales_price',
        'mortgage_price',
        'rental_price',
        'aparat_video',
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
    protected $dates = [
        'assignmented_at',
        'deleted_at'
    ];


    /****************************************
     **         Scopes & Mutators
     ***************************************/

    /**
     * Set the product aparat video link
     *
     * @param  string  $value
     * @return void
     */
    public function setAparatVideoAttribute($value)
    {
        $this->attributes['aparat_video'] = Str::replaceFirst('https://www.aparat.com/v/', '', $value);
    }


    /****************************************
     **             Relations
     ***************************************/

    /**
     * Get all of the users like some products
     */
    public function favorites()
    {
        return $this->belongsToMany(\App\User::class, 'favorites');
    }

    /**
     * Get all the specification table data of the product.
     */
    public function spec_data()
    {
        return $this->hasMany(SpecData::class);
    }

    /**
     * Get all the specification table data of the product.
     */
    public function specifications()
    {
        return $this->hasMany(SpecData::class);
    }

    /**
     * Get the assignment type of the estate
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the estate type of the estate
     */
    public function estate_type()
    {
        return $this->belongsTo(EstateType::class);
    }

    /**
     * Get the label of the product
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    /**
     * Get the office of the estate
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Get all the features of the estate
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }

    /**
     * Get all the detailable features of the estate
     */
    public function detailable_features()
    {
        return $this->belongsToMany(Feature::class)->where('is_detailable', true);
    }

    /**
     * Get the registrar type of estate
     */
    public function registrar_type()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function assignment_informations()
    {
        return $this->belongsToMany(User::class, 'assignment_informations');
    }

    /**
     * Get the city of the estate
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the street of the estate
     */
    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    /**
     * Get all the spec that owned product.
     */
    public function spec()
    {
        return $this->belongsTo(Spec::class);
    }

    /**
     * Get all the media galleries of the model
     */
    public function photos()
    {
        return $this->morphMany(config('medialibrary.media_model'), 'model');
    }


    /****************************************
     **              Methods
     ***************************************/

    /**
     * Create conversions for media library
     *
     * @param Media $media
     * @return void
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this
            ->addMediaConversion('thumb')
            ->keepOriginalImageFormat()
            ->width(100)
            ->height(100)
            ->sharpen(10);

        $this
            ->addMediaConversion('small')
            ->keepOriginalImageFormat()
            ->width(250)
            ->height(250);

        $this
            ->addMediaConversion('medium')
            ->keepOriginalImageFormat()
            ->width(500)
            ->height(500);

        $this
            ->addMediaConversion('large')
            ->keepOriginalImageFormat()
            ->width(1024)
            ->height(800);

        $this
            ->addMediaConversion('wide')
            ->keepOriginalImageFormat()
            ->width(1680)
            ->height(1200);


        $this
            ->addMediaConversion('watermark')
            ->keepOriginalImageFormat()
            ->width(1024)
            ->height(800)
            ->watermark(public_path('images/watermark.png'))
            ->watermarkPosition(Manipulations::POSITION_CENTER)
            ->watermarkOpacity(40)
            ->watermarkHeight(60, Manipulations::UNIT_PERCENT)
            ->watermarkWidth(60, Manipulations::UNIT_PERCENT);
    }
}
