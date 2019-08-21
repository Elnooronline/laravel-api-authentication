<?php

return [
    /**
     * The name of user model.
     */
    'user-model' => \App\User::class,

    /**
     * Determine whether the application support register service.
     */
    'register' => true,

    /**
     * Determine whether the application support password reset service.
     */
    'password-reset' => true,

    /**
     * The resource transformer for the user model.
     */
    'user-resource' => \Elnooronline\LaravelApiAuthentication\Http\Resources\UserResource::class,

    /**
     * The name of onesignal player id field.
     */
    'player-id-field-name' => 'onesignal-player-id',

    /**
     * The urls of the authentications services.
     */
    'urls' => [
        'login' => '/api/login',
        'register' => '/api/register',
        'forget' => '/api/password/forget',
        'check-code' => '/api/password/check-code',
        'reset' => '/api/password/reset',
    ],

    /**
     * The authentications controllers.
     */
    'controllers' => [
        'login' => \Elnooronline\LaravelApiAuthentication\Http\Controllers\Auth\LoginController::class,
        'register' => \Elnooronline\LaravelApiAuthentication\Http\Controllers\Auth\RegisterController::class,
        'forget' => \Elnooronline\LaravelApiAuthentication\Http\Controllers\Auth\ForgotPasswordController::class,
        'reset' => \Elnooronline\LaravelApiAuthentication\Http\Controllers\Auth\ResetPasswordController::class,
    ],

    /**
     * The authentications validation requests.
     */
    'validation' => [
        'login' => \Elnooronline\LaravelApiAuthentication\Http\Requests\LoginRequest::class,
        'register' => \Elnooronline\LaravelApiAuthentication\Http\Requests\RegisterRequest::class,
        'forget' => \Elnooronline\LaravelApiAuthentication\Http\Requests\ForgetPasswordRequest::class,
        'check-code' => \Elnooronline\LaravelApiAuthentication\Http\Requests\CheckCodeRequest::class,
        'reset' => \Elnooronline\LaravelApiAuthentication\Http\Requests\ResetPasswordRequest::class,
    ],
];