<?php

namespace Database\Factories\Examables\Test\Topic;

use App\Domain\Examables\Test\Models\Test;
use Domain\Examables\Test\Topic\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class TopicFactory extends Factory
{
    use WithFaker;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;
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

    public function withTest(array $testAttributes = [])
    {
        return $this->state(function (array $attributes) use ($testAttributes) {
            return [
                'name' => $this->faker->word(),
                'test_id' => Test::factory()->create($testAttributes),
            ];
        });
    }
}
