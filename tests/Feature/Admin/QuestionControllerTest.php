<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
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

    /** @test - админ получает список вопросов */
    public function admin_can_get_questions_list()
    {
        Question::factory()->count(3)->create(['test_id' => $this->test->id]);

        $this->actingAs($this->admin)
            ->getJson(route('admin.questions.index', $this->test))
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test - админ создает вопрос с одиночным выбором */
    public function admin_can_create_single_choice_question()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.questions.store', $this->test), [
                'question_text' => 'Тестовый вопрос?',
                'type' => 'single',
                'points' => 2,
                'answers' => [
                    ['text' => 'Правильный ответ', 'is_correct' => true],
                    ['text' => 'Неправильный ответ', 'is_correct' => false],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('questions', [
            'question_text' => 'Тестовый вопрос?',
            'type' => 'single',
        ]);
    }

    /** @test - админ создает вопрос с множественным выбором */
    public function admin_can_create_multiple_choice_question()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.questions.store', $this->test), [
                'question_text' => 'Вопрос с множественным выбором?',
                'type' => 'multiple',
                'points' => 3,
                'answers' => [
                    ['text' => 'Правильный 1', 'is_correct' => true],
                    ['text' => 'Правильный 2', 'is_correct' => true],
                    ['text' => 'Неправильный', 'is_correct' => false],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('questions', [
            'question_text' => 'Вопрос с множественным выбором?',
            'type' => 'multiple',
        ]);
    }

    /** @test - нельзя создать вопрос без правильного ответа */
    public function admin_cannot_create_question_without_correct_answer()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.questions.store', $this->test), [
                'question_text' => 'Вопрос без правильного ответа?',
                'type' => 'single',
                'points' => 1,
                'answers' => [
                    ['text' => 'Ответ 1', 'is_correct' => false],
                    ['text' => 'Ответ 2', 'is_correct' => false],
                ],
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Должен быть хотя бы один правильный ответ',
            ]);
    }

    /** @test - админ обновляет вопрос с ответами */
    public function admin_can_update_question_with_answers()
    {
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'single',
            'points' => 1,
        ]);

        $oldAnswer = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);

        $this->actingAs($this->admin)
            ->putJson(route('admin.questions.update', $question), [
                'question_text' => 'Обновленный вопрос?',
                'type' => 'single',
                'points' => 2,
                'answers' => [
                    [
                        'id' => $oldAnswer->id,
                        'text' => 'Обновленный ответ',
                        'is_correct' => true,
                    ],
                    [
                        'text' => 'Новый ответ',
                        'is_correct' => false,
                    ],
                ],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Обновленный вопрос?',
            'points' => 2,
        ]);

        $this->assertDatabaseHas('answers', [
            'id' => $oldAnswer->id,
            'answer_text' => 'Обновленный ответ',
        ]);
    }

    /** @test - админ удаляет старые ответы при обновлении */
    public function admin_can_update_question_and_delete_old_answers()
    {
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'single',
        ]);

        $answer1 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $answer2 = Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        $this->actingAs($this->admin)
            ->putJson(route('admin.questions.update', $question), [
                'question_text' => 'Вопрос с удаленным ответом',
                'type' => 'single',
                'points' => 1,
                'answers' => [
                    [
                        'id' => $answer1->id,
                        'text' => 'Оставленный ответ',
                        'is_correct' => true,
                    ],
                ],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('answers', [
            'id' => $answer2->id,
        ]);
    }

    /** @test - админ меняет тип вопроса на множественный */
    public function admin_can_update_question_to_multiple_choice()
    {
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'single',
            'points' => 1,
        ]);

        Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);

        $this->actingAs($this->admin)
            ->putJson(route('admin.questions.update', $question), [
                'question_text' => 'Вопрос с множественным выбором',
                'type' => 'multiple',
                'points' => 2,
                'answers' => [
                    ['text' => 'Правильный 1', 'is_correct' => true],
                    ['text' => 'Правильный 2', 'is_correct' => true],
                    ['text' => 'Неправильный', 'is_correct' => false],
                ],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'type' => 'multiple',
            'points' => 2,
        ]);
    }

    /** @test - админ удаляет вопрос */
    public function admin_can_delete_question()
    {
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
        ]);

        $this->actingAs($this->admin)
            ->deleteJson(route('admin.questions.destroy', $question))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test - админ меняет порядок вопросов */
    public function admin_can_reorder_questions()
    {
        $q1 = Question::factory()->create(['test_id' => $this->test->id, 'order' => 0]);
        $q2 = Question::factory()->create(['test_id' => $this->test->id, 'order' => 1]);

        $this->actingAs($this->admin)
            ->postJson(route('admin.questions.reorder'), [
                'questions' => [
                    ['id' => $q2->id, 'order' => 0],
                    ['id' => $q1->id, 'order' => 1],
                ],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /** @test - админ грузит редактор для одиночного выбора */
    public function admin_can_load_partial_for_single_choice()
    {
        $this->actingAs($this->admin)
            ->postJson(route('admin.questions.partial'), [
                'type' => 'single',
                'question_id' => null,
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['html']);
    }

    /** @test - админ грузит редактор для множественного выбора */
    public function admin_can_load_partial_for_multiple_choice()
    {
        $this->actingAs($this->admin)
            ->postJson(route('admin.questions.partial'), [
                'type' => 'multiple',
                'question_id' => null,
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['html']);
    }

    /** @test - админ грузит редактор с существующим вопросом */
    public function admin_can_load_partial_with_existing_question()
    {
        $question = Question::factory()->create([
            'test_id' => $this->test->id,
            'type' => 'single',
        ]);

        Answer::factory()->create(['question_id' => $question->id]);

        $this->actingAs($this->admin)
            ->postJson(route('admin.questions.partial'), [
                'type' => 'single',
                'question_id' => $question->id,
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['html']);
    }

    /** @test - обычный юзер не может менять вопросы */
    public function non_admin_cannot_manage_questions()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)
            ->getJson(route('admin.questions.index', $this->test))
            ->assertStatus(403);
    }
}
