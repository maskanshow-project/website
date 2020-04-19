<?php

namespace App\Models\Financial;

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

class Payment extends Model implements AuditableContract
{
    use SoftDeletes, Auditable, Filterable;
    use CreateTimeline, CreatorRelationship, ModelHelpersTrait;
    use SearchableTrait;

    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'promocode_id',
        'code',
        'description',
        'amount',
        'ref_id',
        'payment_id',
        'tracking_code',
        'paid_at',
        'expired_at'
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
            'ref_id' => 8,
            'tracking_code' => 10,
            'description' => 4,
            'paid_at' => 6,
            'users.first_name' => 6,
            'users.last_name' => 6,
        ],
        'joins' => [
            'users' => ['users.id', 'payments.user_id']
        ],
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'plan_id',
        'promocode_id',
        'code',
        'description',
        'amount',
        'ref_id',
        'payment_id',
        'tracking_code',
        'paid_at',
        'expired_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'paid_at',
        'expired_at',
        'deleted_at'
    ];

    /****************************************
     **             Relations
     ****************************************/

    /**
     * Get the promocode of the payment
     */
    public function promocode()
    {
        return $this->belongsTo(Promocode::class);
    }

    /**
     * Get the plan of the payment
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the payer of the payment
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
