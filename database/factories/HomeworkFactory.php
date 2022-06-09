<?php

namespace Database\Factories;

use App\Domain\Homework\Models\Homework;
use Domain\Subject\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomeworkFactory extends Factory
{


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Homework::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'observation' => $this->faker->realText(),
            'subject_id' => Subject::factory()->create(),
            'due_date' => $this->faker->dateTimeBetween('now', '+15 days'),
        ];
    }
}
