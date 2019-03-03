<?php

namespace Elnooronline\LaravelApiAuthentication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function requestValidate(Request $request)
    {
        $this->validate(
            $request,
            $request->rules(),
            $request->messages(),
            $request->attributes()
        );
    }
    /**
     * Get the user resource by type.
     *
     * @param $user
     * @return \App\Http\Resources\UserResource
     */
    protected function getUserResource($user)
    {
        $resource = config('api-authentication.user-resource');

        return (new $resource($user))->additional([
            'token' => $user->createToken('Unkown')->accessToken,
        ]);
    }
}