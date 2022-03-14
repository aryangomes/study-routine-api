<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Middleware\TrustProxies;

class UpdateSubjectRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:150', 'unique:subjects,name'],
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
