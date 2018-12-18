<?php
Route::prefix('api')
    ->as('api.')
    ->middleware('api')
    //->namespace('Elnooronline\LaravelApiAuthentication\Http\Controllers')
    ->group(function () {
        Route::post('login', config('api-authentication.controllers.login').'@login')
            ->name('login');

        if (config('api-authentication.register')) {
            Route::post('register', config('api-authentication.controllers.register').'@register')
                ->name('register');
        }
        // password
        Route::post('password/forget', config('api-authentication.controllers.forget').'@sendCode')
            ->name('forget-password');

        Route::post('password/check-code', config('api-authentication.controllers.forget').'@verifyCode')
            ->name('verifyCode');

        Route::post('password/reset', config('api-authentication.controllers.reset').'@reset')
            ->name('reset');
    });