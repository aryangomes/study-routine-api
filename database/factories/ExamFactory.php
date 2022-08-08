<?php

namespace Database\Factories;

use App\Domain\Examables\Essay\Models\Essay;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Domain\Exam\Models\Exam;
use Domain\Subject\Models\Subject;
use App\Domain\Examables\Test\Models\Test;
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
    public function test(array $testAttributes = [])
    {
        return $this->state(function (array $attributes) use ($testAttributes) {

            return [
                'examable_type' => Test::class,
                'examable_id' => Test::factory()->create($testAttributes),
                'subject_id' => $attributes['subject_id'],
                'effective_date' => $attributes['effective_date'],
            ];
        });
    }

    /**
     * Indicate this exam is a group work.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function groupWork(array $groupWorkAttributes = [])
    {
        return $this->state(function (array $attributes) use ($groupWorkAttributes) {

            return [
                'examable_type' => GroupWork::class,
                'examable_id' => GroupWork::factory()->create($groupWorkAttributes),
                'subject_id' => $attributes['subject_id'],
                'effective_date' => $attributes['effective_date'],
            ];
        });
    }

    /**
     * Indicate this exam is a essay.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function essay(array $essayAttributes = [])
    {

        return $this->state(function (array $attributes) use ($essayAttributes) {

            return [
                'examable_type' => Essay::class,
                'examable_id' => Essay::factory()->create($essayAttributes),
                'subject_id' => $attributes['subject_id'],
                'effective_date' => $attributes['effective_date'],

            ];
        });
    }
}
