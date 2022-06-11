<?php

namespace Database\Factories\Examables;

use App\Domain\Examables\Essay\Models\Essay;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Essay::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'topic' => $this->faker->text(20),
            'observation' => $this->faker->realText(),
        ];
    }
}
