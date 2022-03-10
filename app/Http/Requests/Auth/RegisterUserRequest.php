<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => ['string', 'required'],
            'username' => ['string', 'required', 'unique:users'],
            'email' => ['email', 'required', 'unique:users'],
            'password' => ['string', 'required', 'confirmed'],
            'password_confirmation' => ['string', 'required_with:password'],
        ];
    }
}
