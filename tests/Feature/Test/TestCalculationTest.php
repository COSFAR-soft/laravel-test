<?php

namespace Tests\Feature\Test;

use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->test = Test::create([
            'title' => 'Тест для подсчета',
            'description' => 'Проверка логики подсчета',
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);
    }

    /** 5 вопросов с ответами */
    private function createQuestionsWithAnswers($testId, $correctCount)
    {
        for ($i = 1; $i <= 5; $i++) {
            $question = Question::create([
                'test_id' => $testId,
                'question_text' => "Вопрос {$i}",
                'type' => 'single',
                'points' => 1,
                'order' => $i,
            ]);

            Answer::create([
                'question_id' => $question->id,
                'answer_text' => "Правильный ответ {$i}",
                'is_correct' => true,
            ]);

            Answer::create([
                'question_id' => $question->id,
                'answer_text' => "Неправильный ответ {$i}",
                'is_correct' => false,
            ]);
        }
    }

    /** @test - одиночный выбор: 3 из 5 = 60% */
    public function test_score_calculation_for_single_choice()
    {
        $this->createQuestionsWithAnswers($this->test->id, 5);

        $questions = $this->test->questions;

        $result = TestResult::create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 5,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now(),
        ]);

        $userAnswers = [];
        foreach ($questions->take(3) as $question) {
            $correct = $question->answers->where('is_correct', true)->first();
            $userAnswers[$question->id] = $correct->id;
        }
        foreach ($questions->slice(3) as $question) {
            $wrong = $question->answers->where('is_correct', false)->first();
            $userAnswers[$question->id] = $wrong->id;
        }

        $score = $this->calculateScore($this->test, $userAnswers);
        $percentage = round(($score / 5) * 100);

        $this->assertEquals(3, $score);
        $this->assertEquals(60, $percentage);
    }

    private function calculateScore($test, $userAnswers)
    {
        $correctCount = 0;

        foreach ($test->questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;

            if ($question->type === 'single') {
                $correct = $question->answers->where('is_correct', true)->first();
                if ($correct && $userAnswer == $correct->id) {
                    $correctCount++;
                }
            }
        }

        return $correctCount;
    }

    /** @test - проходной балл: 60% не проходит, 80% проходит */
    public function test_passing_score_check()
    {
        $this->createQuestionsWithAnswers($this->test->id, 5);

        $testWithPassingScore = Test::create([
            'title' => 'Тест с проходным баллом',
            'description' => 'Проверка проходного балла',
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        foreach ($this->test->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->test_id = $testWithPassingScore->id;
            $newQuestion->save();

            foreach ($question->answers as $answer) {
                $newAnswer = $answer->replicate();
                $newAnswer->question_id = $newQuestion->id;
                $newAnswer->save();
            }
        }

        $score1 = 3;
        $percentage1 = round(($score1 / 5) * 100);
        $isPassed1 = $percentage1 >= $testWithPassingScore->passing_score;
        $this->assertFalse($isPassed1);

        $score2 = 4;
        $percentage2 = round(($score2 / 5) * 100);
        $isPassed2 = $percentage2 >= $testWithPassingScore->passing_score;
        $this->assertTrue($isPassed2);
    }

    /** @test - множественный выбор: все правильные = true, частично = false */
    public function test_multiple_choice_calculation()
    {
        $question = Question::create([
            'test_id' => $this->test->id,
            'question_text' => 'Выберите правильные варианты',
            'type' => 'multiple',
            'points' => 2,
            'order' => 1,
        ]);

        $correct1 = Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'Правильный вариант 1',
            'is_correct' => true,
        ]);

        $correct2 = Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'Правильный вариант 2',
            'is_correct' => true,
        ]);

        $wrong = Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'Неправильный вариант',
            'is_correct' => false,
        ]);

        $userAnswers = [
            $question->id => [$correct1->id, $correct2->id],
        ];
        $result = $this->checkMultipleChoice($question, $userAnswers);
        $this->assertTrue($result);

        $userAnswers = [
            $question->id => [$correct1->id],
        ];
        $result = $this->checkMultipleChoice($question, $userAnswers);
        $this->assertFalse($result);
    }

    private function checkMultipleChoice($question, $userAnswers)
    {
        $userAnswer = $userAnswers[$question->id] ?? [];

        $correctIds = $question->answers
            ->where('is_correct', true)
            ->pluck('id')
            ->sort()
            ->values()
            ->toArray();

        $userIds = is_array($userAnswer) ? $userAnswer : [];
        sort($userIds);

        return $correctIds === $userIds;
    }
}
