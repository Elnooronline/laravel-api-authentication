<?php

namespace Elnooronline\LaravelApiAuthentication;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Elnooronline\LaravelApiAuthentication\Models\ApiPasswordResetCode;
use Elnooronline\LaravelApiAuthentication\Models\ApiPasswordResetToken;
use Elnooronline\LaravelApiAuthentication\Events\ResetPasswordCodeGenerated;

class ResetPassword
{
    /**
     * Create a verification code and store it into database.
     *
     * @param $email
     * @return string
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \RuntimeException
     */
    public function createVerificationCode($email)
    {
        // Check mobile verification
        $user = $this->checkEmailActivation($email);
        if (! $user) {
            return false;
        }

        // Find the verification code for this email address
        $verification = $this->findCodeByEmail($email);

        // Check if there was a code for this email address,
        // and regenerate it
        if (! empty($verification) && $verification instanceof ApiPasswordResetCode) {
            return $this->regenerateCode($user, $verification);
        }

        // Create a new code for this mobile number
        return $this->createCode($user, $email);
    }

    /**
     * Check the code and create token for this user.
     *
     * @param  string $email
     * @param  string $code
     * @return bool|null
     * @throws \Exception
     */
    public function checkCode($email, $code)
    {
        // find this code&mobile
        $verification = $this->findCode($email, $code);
        // Check if exists and not expired
        if (! $verification || $verification->isExpired()) {
            return null;
        }
        // get the user
        $model = $this->getUserClassName();
        $user = $model::where('email', $email)->first();

        $token = $this->getToken();
        // Create token into database
        $user->apiPasswordResetToken()->create([
            'token' => $token,
        ]);

        // Remove the code
        $this->removeCode($email, $code);

        return $token;
    }

    /**
     * Regenerate verification code for this specific mobile number.
     *
     * @param $user
     * @param ApiPasswordResetCode $verification
     * @return string
     * @throws \RuntimeException
     */
    private function regenerateCode($user, ApiPasswordResetCode $verification)
    {
        $code = $this->getCode();
        $verification->update([
            'email' => $verification->email,
            'code' => $code,
        ]);

        $this->sendMail($user, $code);

        return $code;
    }

    /**
     * Create verification code for this specific mobile number.
     *
     * @param $user
     * @param $email
     * @return string
     * @throws \RuntimeException
     */
    private function createCode($user, $email)
    {
        $code = $this->getCode();

        ApiPasswordResetCode::create([
            'email' => $email,
            'code' => $code,
        ]);

        // send code
        $this->sendMail($user, $code);

        return $code;
    }

    /**
     * Write message to send as sms.
     *
     * @param $user
     * @param $code
     * @return mixed
     */
    public function sendMail($user, $code)
    {
        event(new ResetPasswordCodeGenerated($user, $code));
    }

    /**
     * Check this token, remove it and return the user.
     *
     * @param string $token
     * @return bool|     */
    public function checkToken($token)
    {
        $reset = ApiPasswordResetToken::where('token', $token)->first();

        if (! $reset) {
            return false;
        }

        $this->removeToken($token);

        return $reset->user;
    }

    /**
     * check if this email has been verified by the user.
     *
     * @param $email
     * @return null|\Illuminate\Contracts\Auth\Authenticatable
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function checkEmailActivation($email)
    {
        $model = $this->getUserClassName();

        $user = $model::where('email', $email)->first();

        return $user;
    }

    /**
     * find the code of this email number.
     *
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findCodeByEmail($email)
    {
        return ApiPasswordResetCode::where('email', $email)->first();
    }

    /**
     * @param $email
     * @param $code
     * @return \App\Models\ApiPasswordResetCode|\Illuminate\Database\Eloquent\Model
     */
    public function findCode($email, $code)
    {
        return ApiPasswordResetCode::where('email', $email)
            ->whereRaw("BINARY `code`= ?", [$code])
            ->first();
    }

    /**
     * To remove this code from database.
     *
     * @param $email
     * @param $code
     * @return bool
     */
    public function removeCode($email, $code)
    {
        return ApiPasswordResetCode::where('email', $email)
            ->whereRaw("BINARY `code`= ?", [$code])
            ->delete();
    }

    /**
     * To remove this user token from database.
     *
     * @param $token
     * @return bool
     */
    public function removeToken($token)
    {
        return ApiPasswordResetToken::whereRaw("BINARY `token`= ?", [$token])
            ->delete();
    }

    /**
     * Build verification code.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getCode()
    {
        return str_random(6);
    }

    /**
     * Build verification token.
     *
     * @throws \Exception
     * @return string
     */
    protected function getToken()
    {
        return bin2hex(random_bytes(60));
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
