<?php

namespace Database\Factories;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Domain\Examables\Essay\Models\Essay;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Domain\Homework\Models\Homework;
use Carbon\Carbon;
use Domain\Exam\Models\Exam;
use App\Domain\Examables\Test\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;


class DailyActivityFactory extends Factory
{
    use WithFaker;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DailyActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date_of_activity' => Carbon::today(),
            'start_time' => Carbon::now()->toTimeString(),
            'end_time' => Carbon::now()->addHour()->toTimeString()
        ];
    }

    /**
     * Indicate that a Homework is will be a Daily Activity
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function homework()
    {
        return $this->state(function (array $attributes) {


            $activitable = [
                'activitable_type' => Homework::class,
                'activitable_id' => Homework::factory()->create()
            ];

            return array_merge($attributes, $activitable);
        });
    }

    /**
     * Indicate that a Exam is will be a Daily Activity
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function exam()
    {
        return $this->state(function (array $attributes) {

            $examables = ['test', 'groupWork', 'essay'];

            $examable = $examables[array_rand($examables)];

            $activitable = [
                'activitable_type' => Exam::class,
                'activitable_id' => Exam::factory()->$examable()->create()
            ];

            return array_merge($attributes, $activitable);
        });
    }
}
