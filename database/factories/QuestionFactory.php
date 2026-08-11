<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'test_id' => Test::factory(),
            'question_text' => $this->faker->sentence(6) . '?',
            'type' => $this->faker->randomElement(['single', 'multiple']),
            'points' => $this->faker->numberBetween(1, 3),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
