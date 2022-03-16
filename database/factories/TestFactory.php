<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'exam_id' => Exam::factory()->create(),

        ];
    }
}
