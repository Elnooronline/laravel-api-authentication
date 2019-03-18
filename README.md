# Laravel Api Authentication

> This package used to scaffold basic login, registration and reset password in restful api.

## Installation via [composer](https://getcomposer.org/)

```bash
composer require elnooronline/laravel-api-authentication
```
> Import The following traits in `\App\User` model:
```php

use Illuminate\Foundation\Auth\User as Authenticatable;
use Elnooronline\LaravelApiAuthentication\Models\Traits\HasApiAuthentication;

class User extends Authenticatable
{
    use HasApiAuthentication, Notifiable;
    ...
}
``` 
## Migrations
> You must publish the migration files to create the authentication tables.
```bash
php artisan vendor:publish --tag api-authentication:migration
```
> Then run the following command:
```bash
php artisan migrate && php artisan passport:install
```
## Configration
> After installation run the following command if you want to override any thing in the package. 
```bash
php artisan vendor:publish --tag api-authentication:config
```
```php
// config/api-authentication.php

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
```
> If you want to add your own login or registration or reset password you should create custom comtrollers and requests and extends the package classes.

### Examples
```php
// app/Http/Controllers/Api/LoginController.php

namespace App\Http\Controllers\Api\Auth;

use Elnooronline\LaravelApiAuthentication\Http\Controllers\Auth\LoginController as BaseLoginController;

class LoginController extends BaseLoginController
{
    ...
}
```
Then update the `api-authentication.php` and replace the login controller :
```php
return [
    ...
    'controllers' => [
        'login' => \App\Http\Controllers\Auth\LoginController::class,
        ...
    ],
    ...
];
```
> Also you can add your own user resource class in `api-authentication.php` config file:
```php
return [
    ...
    'user-resource' => \App\Http\Resources\UserResource::class,
    ...
];
```
## Events
> If you want to add envents during the authentication process. You may attach listeners to these events in your `EventServiceProvider`:
```php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Illuminate\Auth\Events\Registered' => [
        'App\Listeners\LogRegisteredUser',
    ],

    'Illuminate\Auth\Events\Login' => [
        'App\Listeners\LogSuccessfulLogin',
    ],

    'Illuminate\Auth\Events\Failed' => [
        'App\Listeners\LogFailedLogin',
    ],

    'Illuminate\Auth\Events\Lockout' => [
        'App\Listeners\LogLockout',
    ],
    
    'Elnooronline\LaravelApiAuthentication\Events\ResetPasswordCodeGenerated' => [
        'Elnooronline\LaravelApiAuthentication\Listeners\ResetPasswordListener',
     ],
];
```
## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
