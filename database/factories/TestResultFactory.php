<?php

namespace Database\Factories;

use App\Models\TestResult;
use App\Models\User;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestResultFactory extends Factory
{
    protected $model = TestResult::class;

    public function definition(): array
    {
        $totalQuestions = $this->faker->numberBetween(5, 20);
        $correctAnswers = $this->faker->numberBetween(0, $totalQuestions);
        $score = round(($correctAnswers / $totalQuestions) * 100);

        return [
            'user_id' => User::factory(),
            'test_id' => Test::factory(),
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'score' => $score,
            'answers' => [],
            'started_at' => now()->subMinutes($this->faker->numberBetween(1, 30)),
            'completed_at' => now(),
        ];
    }
}
