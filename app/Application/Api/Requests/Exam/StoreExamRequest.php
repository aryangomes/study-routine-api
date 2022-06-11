<?php

namespace App\Application\Api\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
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
    public static function rules()
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'effective_date' => ['required', 'after_or_equal:today'],
        ];
    }
}
