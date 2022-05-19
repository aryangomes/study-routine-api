<?php

namespace App\Application\Api\Requests\GroupWork;

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
        return [
            'effective_date' => ['sometimes', 'after_or_equal:today'],
            'topic' => ['sometimes', 'max:150', 'string'],
            'note' => ['max:250', 'string'],
        ];
    }
}
