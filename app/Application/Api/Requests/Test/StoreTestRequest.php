<?php

namespace App\Application\Api\Requests\Test;

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
        return [
            //Exam's rules
            'subject_id' => ['required', 'exists:subjects,id'],
            'effective_date' => ['required', 'after_or_equal:today'],
            //Topic's rules
            'topics' => ['sometimes', 'array'],
            'topics.*.name' => ['string', 'max:150', 'required_with:topics'],

        ];
    }
}
