<?php

namespace App\Application\Api\Requests\Examables\Test;

use App\Application\Api\Requests\Exam\StoreExamRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequest extends FormRequest
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


        $testValidation =
            [
                'topics' => ['sometimes', 'array'],
                'topics.*.name' => ['string', 'max:150', 'required_with:topics'],
            ];

        $testValidation = array_merge(
            $testValidation,
            StoreExamRequest::rules()
        );

        return  $testValidation;
    }
}
