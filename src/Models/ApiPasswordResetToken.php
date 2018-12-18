<?php

namespace Elnooronline\LaravelApiAuthentication\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApiPasswordResetToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
    ];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = null;

    /**
     * Check if this code has been expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->created_at->addMinutes(50)->isPast();
    }

    /**
     * the user who created this token.
     *
     * @return mixed
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function user()
    {
        return $this->belongsTo($this->getUserClassName());
    }

    /**
     * Get the class name of user model.
     *
     * @return string
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getUserClassName()
    {
        $model = Config::get('api-authentication.user-model');
        if (class_exists($model)) {
            return $model;
        }

        throw new ModelNotFoundException();
    }
}
