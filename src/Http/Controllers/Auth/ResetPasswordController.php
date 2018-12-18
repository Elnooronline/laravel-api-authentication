<?php

namespace Elnooronline\LaravelApiAuthentication\Http\Controllers\Auth;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Elnooronline\LaravelApiAuthentication\ResetPassword;

class ResetPasswordController extends Controller
{
    /**
     * @var ResetPassword
     */
    private $resetPassword;

    /**
     * ResetPasswordController constructor.
     * @param ResetPassword $resetPassword
     */
    public function __construct(ResetPassword $resetPassword)
    {
        $this->resetPassword = $resetPassword;
    }

    /**
     * reset a new password.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function reset()
    {
        $forgetPasswordRequest = config('api-authentication.validation.reset');

        $this->requestValidate($forgetPasswordRequest::capture());

        $request = request();

        // Check the token and remove it.
        $user = $this->resetPassword->checkToken($request->token);

        if (!$user) {
            return response([
                'errors' => [
                    'code' => [trans('authentication::passwords.wrong-token')],
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user->password = bcrypt($request->password);

        $user->save();

        return $this->getUserResource($user);
    }
}
