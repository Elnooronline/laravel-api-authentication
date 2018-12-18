<?php

namespace Elnooronline\LaravelApiAuthentication\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceToken extends Model
{
    /**
     * one-signal service name.
     *
     * @const one-signal
     */
    const ONESIGNAL = 'onesignal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'token'];

    /**
     * Get the user who's owns the token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function user()
    {
        return $this->belongsTo($this->getUserClassName());
    }

    /**
     * get the one-signal services.
     *
     * @param $q
     * @return mixed
     */
    public function scopeOnesignal($q)
    {
        return $q->where('name', static::ONESIGNAL);
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
