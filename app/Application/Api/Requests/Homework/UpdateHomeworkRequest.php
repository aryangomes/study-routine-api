<?php

namespace App\Application\Api\Requests\Homework;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeworkRequest extends FormRequest
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
            'title' => ['string', 'max:250'],
            'observation' => ['string', 'max:1000'],
            'due_date' => ['date', 'after_or_equal:today'],
        ];
    }
}
