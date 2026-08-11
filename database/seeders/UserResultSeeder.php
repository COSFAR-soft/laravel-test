<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserResultSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Создание пользователей и результатов...');

        // Получаем все тесты
        $tests = Test::all();

        if ($tests->isEmpty()) {
            $this->command->error('Нет тестов запустите TestSeeder.');
            return;
        }

        // Создаем 50 пользователей с результатами
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'name' => $this->getRandomName(),
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            // Каждый пользователь проходит от 1 до 5 тестов
            $testsToTake = $tests->random(rand(1, min(5, $tests->count())));

            foreach ($testsToTake as $test) {
                $this->createResult($user, $test);
            }
        }

        $this->command->info('Создано 50 пользователей с результатами!');
        $this->command->info('Всего результатов: ' . TestResult::count());
    }

    private function createResult($user, $test)
    {
        $totalQuestions = $test->questions->count();
        $totalPoints = $test->questions->sum('points');

        if ($totalQuestions === 0 || $totalPoints === 0) {
            return;
        }

        // Случайное количество правильных ответов (от 0 до totalQuestions)
        $correctCount = rand(0, $totalQuestions);

        // Случайные набранные баллы (в зависимости от правильных ответов)
        $earnedPoints = 0;
        $allPoints = $test->questions->pluck('points')->toArray();
        shuffle($allPoints);

        for ($i = 0; $i < $correctCount && $i < count($allPoints); $i++) {
            $earnedPoints += $allPoints[$i];
        }

        // Случайный процент (но коррелируем с правильными ответами)
        $baseScore = ($correctCount / $totalQuestions) * 100;
        $randomOffset = rand(-10, 10);
        $score = max(0, min(100, $baseScore + $randomOffset));

        // Случайное время (от 1 до time_limit минут)
        $timeSpent = rand(1, max(2, $test->time_limit));

        // Случайная дата (последние 30 дней)
        $completedAt = Carbon::now()->subDays(rand(0, 30))->subMinutes(rand(0, 1440));

        TestResult::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'score' => round($score),
            'answers' => $this->generateRandomAnswers($test),
            'started_at' => $completedAt->copy()->subMinutes($timeSpent),
            'completed_at' => $completedAt,
        ]);
    }

    private function generateRandomAnswers($test)
    {
        $answers = [];
        foreach ($test->questions as $question) {
            $allAnswers = $question->answers->pluck('id')->toArray();
            if ($question->type === 'single') {
                // Одиночный выбор: выбираем случайный ответ (иногда правильный)
                $answers[$question->id] = $allAnswers[array_rand($allAnswers)];
            } else {
                // Множественный выбор: выбираем от 1 до всех вариантов
                $count = rand(1, count($allAnswers));
                shuffle($allAnswers);
                $answers[$question->id] = array_slice($allAnswers, 0, $count);
            }
        }
        return $answers;
    }

    private function getRandomName()
    {
        $firstNames = ['Алексей', 'Мария', 'Дмитрий', 'Елена', 'Анна', 'Сергей', 'Ирина', 'Владимир', 'Екатерина', 'Николай'];
        $lastNames = ['Иванов', 'Петров', 'Сидоров', 'Кузнецов', 'Смирнов', 'Михайлов', 'Федоров', 'Морозов', 'Волков', 'Алексеев'];

        return $lastNames[array_rand($lastNames)] . ' ' . $firstNames[array_rand($firstNames)];
    }
}
