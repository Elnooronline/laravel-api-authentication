<?php

namespace Elnooronline\LaravelApiAuthentication\Models\Traits;

use Laravel\Passport\HasApiTokens;
use Elnooronline\LaravelApiAuthentication\Models\ServiceToken;
use Elnooronline\LaravelApiAuthentication\Models\ApiPasswordResetToken;

trait HasApiAuthentication
{
    use HasApiTokens;
    /**
     * Determine if the user can login within the api.
     *
     * @return bool
     */
    public function canLoginWithApi()
    {
        return true;
    }

    /**
     * Add a new onesignal token for the user.
     *
     * @param string $token
     * @return $this
     */
    public function addOneSignalToken($token)
    {
        if ($token && preg_match("/^[a-z0-9]{8}-([a-z0-9]{4}-){3}[a-z0-9]{12}$/", $token)) {
            // Delete previous tokens.
            ServiceToken::where('name', ServiceToken::ONESIGNAL)
                ->where(compact('token'))
                ->delete();

            $this->oneSignalTokens()->create([
                'token' => $token,
                'name' => ServiceToken::ONESIGNAL,
            ]);
        }

        return $this;
    }

    /**
     * one signal player id for the user.
     *
     * @return array
     */
    public function routeNotificationForOneSignal()
    {
        return $this->oneSignalTokens()
            ->pluck('token')
            ->toArray();
    }

    /**
     * Get the password-reset-token row for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apiPasswordResetToken()
    {
        return $this->hasMany(ApiPasswordResetToken::class, 'user_id');
    }

    /**
     * Get the service tokens for the current user.
     *
     * @return mixed
     */
    public function serviceTokens()
    {
        return $this->hasMany(ServiceToken::class, 'user_id');
    }

    /**
     * Get the onesignal token for the user.
     *
     * @return mixed
     */
    public function oneSignalTokens()
    {
        return $this->serviceTokens()->onesignal();
    }
}