<?php

namespace App\Application\Api\Requests\Examables\GroupWork\Member;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberGroupWorkRequest extends FormRequest
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
            'user_id' => ['required', 'uuid', 'exists:users,id', 'unique:members_group_work,user_id']
        ];
    }
}
