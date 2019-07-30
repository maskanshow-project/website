<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PasswordReset extends Model
{
    /****************************************
     **             Attributes
     ***************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'expired_at',
    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'expired_at' ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /****************************************
     **             Relations
     ****************************************/

    /**
     * Get the role of this message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
