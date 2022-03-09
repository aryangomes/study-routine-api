<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['sometimes', 'string'],
            'username' => [
                'sometimes',
                'string',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($this->user->id)
            ],
            'password' => ['sometimes', 'string', 'confirmed'],
            'password_confirmation' => ['string', 'required_with:password'],
        ];
    }
}
