<?php

namespace App\Domain\DailyActivity\Rules;

use App\Domain\DailyActivity\Models\DailyActivity;

use Illuminate\Contracts\Validation\Rule;

/**
 * Verify if the Activitable exists
 * 
 * Activitable may be a Homework or 
 * a Examable(Test,Group Work, Essay)
 * 
 */
class ActivitableExists implements Rule
{

    private array $activitables;
    private ?string $activitable;
    private ?string $activitableType;
    private string $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(?string $activitableType)
    {

        $this->activitables = DailyActivity::getActivitables();

        $this->activitableType = $activitableType;

        $this->activitable = null;

        $this->message = 'The activity (Homework or Exam) not exists in our records!';


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
        if (is_null($this->activitable)) {
            return false;
        }

        if (!class_exists($this->activitable)) {
            return false;
        }

        $class = new $this->activitable();

        return !is_null($class::find($value));
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
