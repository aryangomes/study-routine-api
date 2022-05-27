<?php

namespace Database\Factories\Examables;

use App\Domain\Examables\GroupWork\Models\GroupWork;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupWorkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GroupWork::class;

    /**
     * 
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'topic' => $this->faker->words(asText: true),
            'note' => $this->faker->words(asText: true),
        ];
    }
}
