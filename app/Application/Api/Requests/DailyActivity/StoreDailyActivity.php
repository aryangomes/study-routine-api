<?php

namespace App\Application\Api\Requests\DailyActivity;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Domain\DailyActivity\Rules\ActivitableExists;
use App\Domain\DailyActivity\Rules\ActivitableTypeExists;
use App\Domain\DailyActivity\Rules\UniqueDailyActivity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDailyActivity extends FormRequest
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
        $activitableType = $this->input('activitable_type');

        $dateOfActivity = $this->input('date_of_activity');

        return [
            'date_of_activity' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'activitable_type' => ['bail', 'required', new ActivitableTypeExists()],
            'activitable_id' => [
                'bail',
                'required',
                new ActivitableExists($activitableType),
                new UniqueDailyActivity($activitableType, $dateOfActivity)
            ],
        ];
    }
}
