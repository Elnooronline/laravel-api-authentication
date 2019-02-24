<?php

namespace Elnooronline\LaravelApiAuthentication\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|string',
            'password' => 'required|confirmed',
            config('api-authentication.player-id-field-name') => 'nullable|onesignal_player_id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => trans('authentication::passwords.attributes.email'),
            'code' => trans('authentication::passwords.attributes.code'),
            'password' => trans('authentication::passwords.attributes.password'),
            'token' => trans('authentication::passwords.attributes.token'),
        ];
    }
}
