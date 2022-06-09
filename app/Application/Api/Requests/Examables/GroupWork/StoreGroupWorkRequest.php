<?php

namespace App\Application\Api\Requests\Examables\GroupWork;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupWorkRequest extends FormRequest
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
            //Exam's rules
            'subject_id' => ['required', 'exists:subjects,id'],
            'effective_date' => ['required', 'after_or_equal:today'],
            //Group Work's rules 
            'topic' => ['required', 'max:150', 'string'],
            'note' => ['max:250', 'string'],
            'members' => ['sometimes', 'array'],
            'members.*.user_id' => ['exists:users,id', 'required_with:topics'],
        ];
    }
}
