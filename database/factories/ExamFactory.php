<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Examables\Test;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class ExamFactory extends Factory
{

    use WithFaker;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $examables = [
            Test::class,
        ];

        $examableType = $this->faker->randomElement($examables);
        $examable = $examableType::factory()->create();

        return [
            'examable_id' => $examable->id,
            'examable_type' => $examableType,
            'subject_id' => Subject::factory()->create(),
            'effective_date' => $this->faker->dateTimeBetween('now', '+15 days'),
        ];
    }
}
