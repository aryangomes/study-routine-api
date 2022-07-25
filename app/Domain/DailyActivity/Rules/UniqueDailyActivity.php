<?php

namespace App\Domain\DailyActivity\Rules;

use App\Domain\DailyActivity\Models\DailyActivity;
use Date;
use Illuminate\Contracts\Validation\Rule;

class UniqueDailyActivity implements Rule
{

    private array $activitables;
    private ?string $activitable;
    private ?string $activitableType;
    private string $message;
    private ?string $dateOfActivity;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?string $activitableType, ?string $dateOfActivity)
    {

        $this->activitables = DailyActivity::getActivitables();

        $this->activitableType = $activitableType;

        $this->dateOfActivity = $dateOfActivity;

        $this->activitable = null;

        $this->message =
            'This activity is a Daily Activity already.';


        if ($this->activitableTypeIsValid()) {

            $this->activitable = $this->activitables[$activitableType];
        }
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
        if (is_null($this->dateOfActivity)) {
            $this->message('The date of activity field is required.');
            return false;
        }

        $countDailyActivity = DailyActivity::where([
            $attribute => $value,
            'activitable_type' => $this->activitable,
            'date_of_activity' => $this->dateOfActivity
        ])->count();

        return $countDailyActivity === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }


    private function activitableTypeIsValid(): bool
    {

        if (is_null($this->activitableType)) {

            $this->message = 'The activitable type field is required.';

            return false;
        }

        $activitableTypeExistsRule = new ActivitableTypeExists();

        $activitableTypeExists =
            $activitableTypeExistsRule->passes('activitable_type', $this->activitableType);


        if (!$activitableTypeExists) {
            $this->message = $activitableTypeExistsRule->message();

            return false;
        }


        return true;
    }
}
