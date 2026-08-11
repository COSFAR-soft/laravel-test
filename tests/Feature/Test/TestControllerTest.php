<?php

namespace Tests\Feature\Test;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->test = Test::factory()->create([
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);
    }

    // ============================================
    // AUTO SUBMIT (ИСТЕЧЕНИЕ ВРЕМЕНИ)
    // ============================================

    /** @test */
    public function test_auto_submit_when_time_expires()
    {
        // Создаём вопросы с ответами
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'single',
            'points' => 1,
        ]);

        $correctAnswer = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        // Создаём результат с истекшим временем
        $result = TestResult::factory()->create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [$question->id => $correctAnswer->id],
            'started_at' => now()->subMinutes(60), // 60 минут назад (время вышло)
            'completed_at' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('tests.take', $this->test));

        $response->assertRedirect(route('tests.results', $this->test));
        $response->assertSessionHas('warning');

        $result->refresh();
        $this->assertNotNull($result->completed_at);
        $this->assertEquals(1, $result->correct_answers);
        $this->assertEquals(100, $result->score);
    }

    /** @test */
    public function test_auto_submit_with_multiple_choice_questions()
    {
        // Создаём вопрос с множественным выбором
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'multiple',
            'points' => 2,
        ]);

        $correct1 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $correct2 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $wrong = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        // Создаём результат с истекшим временем
        $result = TestResult::factory()->create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [$question->id => [$correct1->id, $correct2->id]],
            'started_at' => now()->subMinutes(60),
            'completed_at' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('tests.take', $this->test));

        $response->assertRedirect(route('tests.results', $this->test));
        $response->assertSessionHas('warning');

        $result->refresh();
        $this->assertNotNull($result->completed_at);
        $this->assertEquals(1, $result->correct_answers);
        $this->assertEquals(100, $result->score);
    }

    // ============================================
    // SUBMIT С МНОЖЕСТВЕННЫМ ВЫБОРОМ
    // ============================================

    /** @test */
    public function test_submit_multiple_choice_correct()
    {
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'multiple',
            'points' => 2,
        ]);

        $correct1 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $correct2 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $wrong = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        $result = TestResult::factory()->create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now()->subMinutes(5),
            'completed_at' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('tests.submit', $this->test), [
                'answers' => [
                    $question->id => [$correct1->id, $correct2->id],
                ],
            ]);

        $response->assertRedirect(route('tests.results', $this->test));

        $result->refresh();
        $this->assertEquals(1, $result->correct_answers);
        $this->assertEquals(100, $result->score);
    }

    /** @test */
    public function test_submit_multiple_choice_partially_correct()
    {
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'multiple',
            'points' => 2,
        ]);

        $correct1 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $correct2 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $wrong = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        $result = TestResult::factory()->create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now()->subMinutes(5),
            'completed_at' => null,
        ]);

        // Пользователь выбрал только один правильный ответ
        $response = $this->actingAs($this->user)
            ->post(route('tests.submit', $this->test), [
                'answers' => [
                    $question->id => [$correct1->id],
                ],
            ]);

        $response->assertRedirect(route('tests.results', $this->test));

        $result->refresh();
        $this->assertEquals(0, $result->correct_answers);
        $this->assertEquals(0, $result->score);
    }
}
