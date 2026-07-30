<?php

namespace Tests\Feature\Test;

use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class TestAPITest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->test = Test::create([
            'title' => 'API Тест по Laravel',
            'description' => 'Тестирование API эндпоинтов',
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $question = Question::create([
            'test_id' => $this->test->id,
            'question_text' => 'Вопрос для API?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);

        Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'Правильный ответ',
            'is_correct' => true,
        ]);
        Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'Неправильный ответ',
            'is_correct' => false,
        ]);
    }

    /** @test */
    public function api_can_get_list_of_tests()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/tests');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'time_limit',
                        'passing_score',
                        'questions_count',
                    ],
                ],
                'meta' => [
                    'total',
                ],
            ]);
    }

    /** @test */
    public function api_returns_401_if_not_authenticated()
    {
        $response = $this->getJson('/api/tests');
        $response->assertStatus(401);
    }

    /** @test */
    public function api_can_get_test_details()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/tests/{$this->test->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'time_limit',
                    'passing_score',
                    'questions_count',
                    'questions' => [
                        '*' => [
                            'id',
                            'question_text',
                            'type',
                            'points',
                            'answers' => [
                                '*' => [
                                    'id',
                                    'answer_text',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function api_can_start_test()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/tests/{$this->test->id}/start");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'test_id',
                    'user_id',
                    'total_questions',
                    'status',
                ],
                'message',
            ]);
    }

    /** @test */
    public function api_cannot_start_test_twice()
    {
        Sanctum::actingAs($this->user);

        $this->postJson("/api/tests/{$this->test->id}/start");
        $response = $this->postJson("/api/tests/{$this->test->id}/start");

        $response->assertStatus(409);
    }

    /** @test */
    public function api_can_get_test_questions()
    {
        Sanctum::actingAs($this->user);

        $this->postJson("/api/tests/{$this->test->id}/start");

        $response = $this->getJson("/api/tests/{$this->test->id}/questions");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'question_text',
                        'type',
                        'points',
                        'answers' => [
                            '*' => [
                                'id',
                                'answer_text',
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /** @test */
    public function api_can_submit_answers()
    {
        Sanctum::actingAs($this->user);

        $this->postJson("/api/tests/{$this->test->id}/start");

        $question = $this->test->questions->first();
        $correctAnswer = $question->answers->where('is_correct', true)->first();

        $response = $this->postJson("/api/tests/{$this->test->id}/submit", [
            'answers' => [
                $question->id => $correctAnswer->id,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'score',
                    'correct_answers',
                    'total_questions',
                    'percentage',
                    'is_passed',
                ],
                'message',
            ]);
    }

    /** @test */
    public function api_can_get_test_results()
    {
        Sanctum::actingAs($this->user);

        TestResult::create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 1,
            'score' => 100,
            'answers' => [],
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
        ]);

        $response = $this->getJson("/api/tests/{$this->test->id}/results");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'score',
                    'correct_answers',
                    'total_questions',
                    'percentage',
                    'is_passed',
                    'test' => [
                        'id',
                        'title',
                    ],
                ],
            ]);
    }

    /** @test */
    public function api_returns_validation_error_when_submitting_invalid_answers()
    {
        Sanctum::actingAs($this->user);

        $this->postJson("/api/tests/{$this->test->id}/start");

        $response = $this->postJson("/api/tests/{$this->test->id}/submit", [
            'answers' => [],
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function api_cannot_submit_twice()
    {
        Sanctum::actingAs($this->user);

        $this->postJson("/api/tests/{$this->test->id}/start");

        $question = $this->test->questions->first();
        $correctAnswer = $question->answers->where('is_correct', true)->first();

        $this->postJson("/api/tests/{$this->test->id}/submit", [
            'answers' => [$question->id => $correctAnswer->id],
        ]);

        $response = $this->postJson("/api/tests/{$this->test->id}/submit", [
            'answers' => [$question->id => $correctAnswer->id],
        ]);

        $response->assertStatus(404);
    }
}
