<?php

namespace App\Domain\DailyActivity\Rules;

use App\Domain\DailyActivity\Models\DailyActivity;
use Illuminate\Contracts\Validation\Rule;

class ActivitableTypeExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        return key_exists($value, DailyActivity::getActivitables());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The activitable type field must be one these values: ' .
            implode(',', array_keys(DailyActivity::getActivitables()));
    }
}
