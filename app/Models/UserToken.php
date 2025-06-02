<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $token_no
 * @property int $expire_at
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 */
class UserToken extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_token';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_no',
        'expire_at',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        'token_no',
        'user_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = ['token_no' => 'int'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expire_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * expire_at Mutator
     *
     * @param integer $days
     *
     * @return void
     */
    public function setExpireAtAttribute(int $days)
    {
        $this->attributes['expire_at'] = Carbon::now()->addDays($days);
    }

    /**
     * Get the user that owns the UserToken
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * is expire_at ?
     *
     * @return boolean
     */
    public function isExpired()
    {
        $tmp = new Carbon($this->attributes['expire_at']);
        return $tmp->isPast();
    }

}
