<?php

namespace App\Application\Api\Requests\Examables\Essay;

use App\Application\Api\Requests\Exam\UpdateExamRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEssayRequest extends FormRequest
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


        $essayValidation =
            [
                'topic' => 'string|max:250',
                'observation' => 'string|max:1000',
            ];

        $essayValidation = array_merge(
            $essayValidation,
            UpdateExamRequest::rules()
        );

        return  $essayValidation;
    }
}
