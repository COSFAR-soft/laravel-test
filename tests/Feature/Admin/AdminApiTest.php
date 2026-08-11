<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        $this->test = Test::factory()->create();
    }

    /** @test - админ получает список тестов через API */
    public function admin_can_get_tests_list_via_api()
    {
        Sanctum::actingAs($this->admin);

        Test::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/tests');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'time_limit',
                        'passing_score',
                        'is_published',
                        'questions_count',
                        'created_at',
                    ],
                ],
                'meta' => [
                    'total',
                    'current_page',
                    'last_page',
                ],
            ]);
    }

    /** @test - админ создает тест через API */
    public function admin_can_create_test_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/admin/tests', [
            'title' => 'API Тест',
            'description' => 'Описание API теста',
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'time_limit',
                    'passing_score',
                    'is_published',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('tests', [
            'title' => 'API Тест',
            'is_published' => true,
        ]);
    }

    /** @test - админ смотрит детали теста через API */
    public function admin_can_view_test_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson("/api/admin/tests/{$this->test->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'time_limit',
                    'passing_score',
                    'is_published',
                    'questions_count',
                    'questions' => [
                        '*' => [
                            'id',
                            'question_text',
                            'type',
                            'points',
                            'order',
                            'answers' => [
                                '*' => [
                                    'id',
                                    'answer_text',
                                    'is_correct',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }

    /** @test - админ обновляет тест через API */
    public function admin_can_update_test_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/admin/tests/{$this->test->id}", [
            'title' => 'Обновленный API Тест',
            'description' => 'Новое описание',
            'time_limit' => 45,
            'passing_score' => 80,
            'is_published' => false,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'time_limit',
                    'passing_score',
                    'is_published',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('tests', [
            'id' => $this->test->id,
            'title' => 'Обновленный API Тест',
            'passing_score' => 80,
        ]);
    }

    /** @test - админ удаляет тест через API */
    public function admin_can_delete_test_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/admin/tests/{$this->test->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Тест удален']);

        $this->assertDatabaseMissing('tests', ['id' => $this->test->id]);
    }

    /** @test - админ получает вопросы теста через API */
    public function admin_can_get_questions_via_api()
    {
        Sanctum::actingAs($this->admin);

        Question::factory()->count(3)->create(['test_id' => $this->test->id]);

        $response = $this->getJson("/api/admin/tests/{$this->test->id}/questions");

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'question_text',
                    'type',
                    'points',
                    'order',
                    'answers' => [
                        '*' => [
                            'id',
                            'answer_text',
                            'is_correct',
                        ],
                    ],
                ],
            ]);
    }

    /** @test - админ создает вопрос через API */
    public function admin_can_create_question_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson("/api/admin/tests/{$this->test->id}/questions", [
            'question_text' => 'API Вопрос?',
            'type' => 'single',
            'points' => 2,
            'answers' => [
                ['text' => 'Правильный', 'is_correct' => true],
                ['text' => 'Неправильный', 'is_correct' => false],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'question_text',
                    'type',
                    'points',
                    'order',
                    'answers',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('questions', [
            'question_text' => 'API Вопрос?',
            'type' => 'single',
        ]);
    }

    /** @test - админ создает вопрос с множественным выбором через API */
    public function admin_can_create_multiple_choice_question_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson("/api/admin/tests/{$this->test->id}/questions", [
            'question_text' => 'API Вопрос с множественным выбором?',
            'type' => 'multiple',
            'points' => 3,
            'answers' => [
                ['text' => 'Правильный 1', 'is_correct' => true],
                ['text' => 'Правильный 2', 'is_correct' => true],
                ['text' => 'Неправильный', 'is_correct' => false],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'type', 'answers'], 'message']);

        $this->assertDatabaseHas('questions', [
            'question_text' => 'API Вопрос с множественным выбором?',
            'type' => 'multiple',
        ]);
    }

    /** @test - API не дает создать вопрос без правильного ответа */
    public function admin_cannot_create_question_without_correct_answer_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson("/api/admin/tests/{$this->test->id}/questions", [
            'question_text' => 'Вопрос без правильного ответа?',
            'type' => 'single',
            'points' => 1,
            'answers' => [
                ['text' => 'Ответ 1', 'is_correct' => false],
                ['text' => 'Ответ 2', 'is_correct' => false],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['answers']);
    }

    /** @test - админ обновляет вопрос через API */
    public function admin_can_update_question_via_api()
    {
        Sanctum::actingAs($this->admin);

        $question = Question::factory()->create(['test_id' => $this->test->id]);
        $answer = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);

        $response = $this->putJson("/api/admin/questions/{$question->id}", [
            'question_text' => 'Обновленный API Вопрос?',
            'type' => 'single',
            'points' => 3,
            'answers' => [
                [
                    'id' => $answer->id,
                    'text' => 'Обновленный ответ',
                    'is_correct' => true,
                ],
                [
                    'text' => 'Новый ответ',
                    'is_correct' => false,
                ],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'question_text', 'points'], 'message']);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Обновленный API Вопрос?',
            'points' => 3,
        ]);
    }

    /** @test - админ удаляет вопрос через API */
    public function admin_can_delete_question_via_api()
    {
        Sanctum::actingAs($this->admin);

        $question = Question::factory()->create(['test_id' => $this->test->id]);

        $response = $this->deleteJson("/api/admin/questions/{$question->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Вопрос удален']);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test - админ меняет порядок вопросов через API */
    public function admin_can_reorder_questions_via_api()
    {
        Sanctum::actingAs($this->admin);

        $q1 = Question::factory()->create(['test_id' => $this->test->id, 'order' => 0]);
        $q2 = Question::factory()->create(['test_id' => $this->test->id, 'order' => 1]);

        $response = $this->postJson("/api/admin/questions/reorder", [
            'questions' => [
                ['id' => $q2->id, 'order' => 0],
                ['id' => $q1->id, 'order' => 1],
            ],
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Порядок обновлен']);

        $this->assertDatabaseHas('questions', [
            'id' => $q2->id,
            'order' => 0,
        ]);
    }

    /** @test - админ получает статистику через API */
    public function admin_can_get_statistics_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/admin/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_tests',
                    'published_tests',
                    'total_users',
                    'total_attempts',
                    'completed_attempts',
                    'avg_score',
                    'passed_count',
                    'failed_count',
                ],
            ]);
    }

    /** @test - админ получает статистику по тесту через API */
    public function admin_can_get_test_statistics_via_api()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson("/api/admin/tests/{$this->test->id}/statistics");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_attempts',
                    'completed_attempts',
                    'avg_score',
                    'max_score',
                    'min_score',
                    'passed_count',
                    'failed_count',
                ],
            ]);
    }

    /** @test - обычный юзер не может управлять тестами через API */
    public function non_admin_cannot_manage_tests_via_api()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/admin/tests');
        $response->assertStatus(403);

        $response = $this->postJson('/api/admin/tests', [
            'title' => 'Тест от юзера',
            'time_limit' => 30,
            'passing_score' => 70,
        ]);
        $response->assertStatus(403);
    }

    /** @test - неавторизованный юзер не может управлять тестами через API */
    public function unauthenticated_cannot_manage_tests_via_api()
    {
        $response = $this->getJson('/api/admin/tests');
        $response->assertStatus(401);

        $response = $this->postJson('/api/admin/tests', [
            'title' => 'Тест без токена',
            'time_limit' => 30,
            'passing_score' => 70,
        ]);
        $response->assertStatus(401);
    }
}
