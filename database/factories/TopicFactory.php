<?php

namespace Database\Factories;

use App\Models\Examables\Test;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class TopicFactory extends Factory
{
    use WithFaker;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),

        ];
    }
}
