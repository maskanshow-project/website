<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use App\Models\{
    Blog\Article,
    Opinion\Comment,
    Places\City,
    Estate\Estate,
    Financial\Plan,
    User\Message,
    User\Office
};
use EloquentFilter\Filterable;
use App\Helpers\CreateTimeline;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Helpers\MediaConversionsTrait;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Helpers\ModelHelpersTrait;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class User extends Authenticatable implements AuditableContract, HasMedia
{
    use LaratrustUserTrait, HasApiTokens, Filterable, CreateTimeline;
    use Notifiable, SoftDeletes, Auditable, ModelHelpersTrait;
    use HasMediaTrait, MediaConversionsTrait, SearchableTrait;
    use SoftCascadeTrait;

    /****************************************
     **             Attributes
     ***************************************/


    protected static $has_user = false;

    /**
     * The attributes specifies that table has char type id
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The attributes defines use uuid when creating
     * or auto increment integer
     *
     * @var boolean
     */
    protected static $create_uuid = true;
    
    /**
     * The relations that must have soft deleted with with model.
     *
     * @var array
     */
    protected $softCascade = [
        'articles',
        'offices',
        'comments',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'office_id',
        'city_id',
        'plan_id',
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'username',
        'email',
        'password',
        'national_code',
        'gender',
        'validity_end_at',
        'last_payment',
        'is_public_info',
        'system_authentication_code'
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
            'first_name' => 10,
            'last_name' => 10,
            'email' => 10,
            'address' => 3,
            'phone_number' => 5,
            'username' => 8,
            'national_code' => 10,
        ],
        'joins' => [
            // 'brand_translations' => ['brands.id','brand_translations.brand_id'],
        ],
    ];
    
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'city_id',
        'first_name',
        'last_name',
        'social_links',
        'email',
        'password',
        'national_code',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phones'        => 'array',
        'social_links'  => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'validity_end_at',
        'last_payment',
        'deleted_at',
    ];

    
    /****************************************
     **         Scopes & Mutators
     ***************************************/
    
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }


    /****************************************
     **             Relations
     ***************************************/
    
    /**
     * Get all the articles that the user wrote them
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get all the office that owned by the user
     */
    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    /**
     * Get the office that the user work in
     */
    public function workplace()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    /**
     * Get all of the favorites products
     */
    public function favorites()
    {
        return $this->belongsToMany(Estate::class, 'favorites');
    }

    /**
     * Get all the audits that the user wrote them
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * each user live in one city
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the current plan of the user
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the media field of the model
     */
    public function avatar()
    {
        return $this->morphMany(config('medialibrary.media_model'), 'model');
    }

    /**
     * Get the parent member of the user
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get all the members of the user
     */
    public function members()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get all the visited estates of the user
     */
    public function visitedEstates()
    {
        return $this->belongsToMany(Estate::class, 'visited_estates');
    }
}