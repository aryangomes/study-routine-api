<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Middleware\TrustProxies;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->id() === $this->subject->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => [
                'sometimes', 'required', 'string', 'max:150',
                Rule::unique('subjects')->where(function ($query) {
                    return $query->where('user_id', $this->subject->user_id);
                }),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => __('crud_model_operations.unique', [
                'model' => 'Subject'
            ]),
        ];
    }
}
