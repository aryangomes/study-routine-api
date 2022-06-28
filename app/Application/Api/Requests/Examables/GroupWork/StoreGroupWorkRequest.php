<?php

namespace App\Application\Api\Requests\Examables\GroupWork;

use App\Application\Api\Requests\Exam\StoreExamRequest;
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

        $groupWorkValidation =
            [
                'topic' => ['required', 'max:150', 'string'],
                'note' => ['max:250', 'string'],
                'members' => ['sometimes', 'array'],
                'members.*.user_id' => ['required_with:members', 'uuid', 'exists:users,id'],

            ];

        $groupWorkValidation = array_merge(
            $groupWorkValidation,
            StoreExamRequest::rules()
        );

        return  $groupWorkValidation;
    }
}
