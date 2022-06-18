<?php

namespace App\Application\Api\Requests\DailyActivity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDailyActivity extends FormRequest
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
            'date_of_activity' => ['date', 'after_or_equal:today'],
            'start_time' => ['date_format:H:i:s'],
            'end_time' => ['date_format:H:i:s', 'after:start_time'],
        ];
    }
}
