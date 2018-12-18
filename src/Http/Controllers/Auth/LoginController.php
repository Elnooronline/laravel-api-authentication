<?php

namespace Elnooronline\LaravelApiAuthentication\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $forgetPasswordRequest = config('api-authentication.validation.login');

        $this->requestValidate($forgetPasswordRequest::capture());
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     * @return void|\App\Http\Resources\UserResource
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $user = $this->guard()->user();

        $user->addOneSignalToken(
            $request->input(config('api-authentication.player-id-field-name'))
        );

        if ($user && $user->canLoginWithApi()) {

            return $this->getUserResource($user);
        }

        $this->sendFailedLoginResponse($request);
    }
}
