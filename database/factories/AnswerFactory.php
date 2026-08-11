<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'answer_text' => $this->faker->sentence(4),
            'is_correct' => $this->faker->boolean(30),
        ];
    }
}
