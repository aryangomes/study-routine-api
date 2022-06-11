<?php

namespace Database\Factories;

use App\Domain\Examables\Essay\Models\Essay;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use Domain\Examables\Test\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class ExamFactory extends Factory
{

    use WithFaker;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'subject_id' => Subject::factory()->create(),
            'effective_date' => $this->faker->dateTimeBetween('now', '+15 days'),
        ];
    }

    /**
     * Indicate this exam is a test.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function test()
    {
        return $this->state(function (array $attributes) {

            return [
                'examable_type' => Test::class,
                'examable_id' => Test::factory()->create(),
                'subject_id' => Subject::factory()->create(),
                'effective_date' => $this->faker->dateTimeBetween('now', '+15 days'),

            ];
        });
    }

    /**
     * Indicate this exam is a group work.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function groupWork()
    {
        return $this->state(function (array $attributes) {

            return [
                'examable_type' => GroupWork::class,
                'examable_id' => GroupWork::factory()->create(),
                'subject_id' => Subject::factory()->create(),
                'effective_date' => $this->faker->dateTimeBetween('now', '+15 days'),

            ];
        });
    }

    /**
     * Indicate this exam is a essay.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function essay()
    {
        return $this->state(function (array $attributes) {

            return [
                'examable_type' => Essay::class,
                'examable_id' => Essay::factory()->create(),
                'subject_id' => $attributes['subject_id'],
                'effective_date' => $attributes['effective_date'],

            ];
        });
    }
}
