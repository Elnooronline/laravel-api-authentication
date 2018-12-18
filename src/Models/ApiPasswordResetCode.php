<?php

namespace Elnooronline\LaravelApiAuthentication\Models;

use Illuminate\Database\Eloquent\Model;

class ApiPasswordResetCode extends Model
{
    /**
     * the code expiration by seconds.
     *
     * @const int
     */
    const EXPIRE_DURATION = 10 * 60;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = null;



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'mobile',
        'code',
    ];

    /**
     * Check if this code has been expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->updated_at->addMinutes(10)->isPast();
    }
}
