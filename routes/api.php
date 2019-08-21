<?php
Route::as('api.')
    ->middleware('api')
    //->namespace('Elnooronline\LaravelApiAuthentication\Http\Controllers')
    ->group(function () {
        Route::post(
            config('api-authentication.urls.login'),
            config('api-authentication.controllers.login').'@login'
        )->name('login');

        if (config('api-authentication.register')) {
            Route::post(
                config('api-authentication.urls.register'),
                config('api-authentication.controllers.register').'@register'
            )->name('register');
        }
        
        // password reset
        if (config('api-authentication.password-reset')) {
            Route::post(
                config('api-authentication.urls.forget'),
                config('api-authentication.controllers.forget') . '@sendCode'
            )->name('forget-password');

            Route::post(
                config('api-authentication.urls.check-code'),
                config('api-authentication.controllers.forget') . '@verifyCode'
            )->name('verifyCode');

            Route::post(
                config('api-authentication.urls.reset'),
                config('api-authentication.controllers.reset') . '@reset'
            )->name('reset');
        }
    });