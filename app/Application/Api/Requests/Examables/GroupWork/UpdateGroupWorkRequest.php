<?php

namespace App\Application\Api\Requests\Examables\GroupWork;

use App\Application\Api\Requests\Exam\UpdateExamRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupWorkRequest extends FormRequest
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
                'topic' => ['sometimes', 'max:150', 'string'],
                'note' => ['max:250', 'string'],
            ];

        $groupWorkValidation = array_merge(
            $groupWorkValidation,
            UpdateExamRequest::rules()
        );

        return  $groupWorkValidation;
    }
}
