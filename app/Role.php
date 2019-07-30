<?php

namespace App;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Laratrust\Models\LaratrustRole;
use EloquentFilter\Filterable;
use App\Helpers\CreateTimeline;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Helpers\ModelHelpersTrait;
use App\Models\User\Message;

class Role extends LaratrustRole implements AuditableContract
{
    use SoftDeletes ,Auditable, Filterable, CreateTimeline;
    use Translatable, ModelHelpersTrait, SearchableTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active'
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
            'role_translations.display_name' => 10,
            'role_translations.description' => 8,
        ],
        'joins' => [
            'role_translations' => ['roles.id','role_translations.role_id']
        ],
    ];

    /**
     * The attributes that are store in the transltion model.
     *
     * @var array
     */
    public $translatedAttributes = [
        'display_name',
        'description',
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'name',
        'display_name',
        'description',
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

    
    /****************************************
     **             Relations
     ****************************************/

    /**
     * Get all the messages of the role
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
