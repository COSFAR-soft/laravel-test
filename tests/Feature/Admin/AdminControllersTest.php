<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllersTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
        ]);
    }

    /** @test */
    public function admin_can_view_dashboard()
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(200)
            ->assertSee('Статистика');
    }

    /** @test */
    public function dashboard_shows_correct_statistics()
    {
        // Создаём тесты
        Test::factory()->count(3)->create(['is_published' => true]);
        Test::factory()->count(1)->create(['is_published' => false]);

        // Создаём пользователей и результаты
        $user = User::factory()->create();
        $test = Test::first();
        TestResult::factory()->count(5)->create([
            'test_id' => $test->id,
            'user_id' => $user->id,
            'completed_at' => now(),
            'score' => 80,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(200)
            ->assertSee('3')  // Опубликовано
            ->assertSee('5')  // Прохождений
            ->assertSee('80%'); // Средний балл
    }


    /** @test */
    public function admin_can_view_tests_list()
    {
        Test::factory()->count(5)->create();

        $this->actingAs($this->admin)
            ->get(route('admin.tests.index'))
            ->assertStatus(200)
            ->assertSee('Тесты');
    }

    /** @test */
    public function admin_can_create_test()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.tests.store'), [
                'title' => 'Новый тест',
                'description' => 'Описание теста',
                'time_limit' => 30,
                'passing_score' => 70,
                'is_published' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tests', [
            'title' => 'Новый тест',
            'is_published' => true,
        ]);
    }

    /** @test */
    public function admin_can_edit_test()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.tests.edit', $test))
            ->assertStatus(200)
            ->assertSee($test->title);
    }

    /** @test */
    public function admin_can_update_test()
    {
        $test = Test::factory()->create(['title' => 'Старое название']);

        $this->actingAs($this->admin)
            ->put(route('admin.tests.update', $test), [
                'title' => 'Новое название',
                'description' => $test->description,
                'time_limit' => 30,
                'passing_score' => 70,
                'is_published' => true,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tests', [
            'id' => $test->id,
            'title' => 'Новое название',
        ]);
    }

    /** @test */
    public function admin_can_delete_test()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.tests.destroy', $test))
            ->assertRedirect();

        $this->assertDatabaseMissing('tests', ['id' => $test->id]);
    }

    /** @test */
    public function admin_can_access_constructor()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.tests.constructor', $test))
            ->assertStatus(200)
            ->assertSee('Конструктор');
    }

    /** @test */
    public function admin_can_get_questions_for_test()
    {
        $test = Test::factory()->create();
        $question = Question::factory()->create(['test_id' => $test->id]);

        $this->actingAs($this->admin)
            ->getJson(route('admin.questions.index', $test))
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    /** @test */
    public function admin_can_store_question()
    {
        $test = Test::factory()->create();

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.questions.store', $test), [
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

    /** @test */
    public function admin_can_update_question()
    {
        $question = Question::factory()->create();

        $this->actingAs($this->admin)
            ->putJson(route('admin.questions.update', $question), [
                'question_text' => 'Обновленный вопрос?',
                'type' => 'single',
                'points' => 3,
                'answers' => [
                    ['text' => 'Новый ответ', 'is_correct' => true],
                ],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'question_text' => 'Обновленный вопрос?',
            'points' => 3,
        ]);
    }

    /** @test */
    public function admin_can_delete_question()
    {
        $question = Question::factory()->create();

        $this->actingAs($this->admin)
            ->deleteJson(route('admin.questions.destroy', $question))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    /** @test */
    public function admin_can_reorder_questions()
    {
        $test = Test::factory()->create();
        $q1 = Question::factory()->create(['test_id' => $test->id, 'order' => 0]);
        $q2 = Question::factory()->create(['test_id' => $test->id, 'order' => 1]);

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

    /** @test */
    public function admin_can_view_users_list()
    {
        User::factory()->count(5)->create();

        $this->actingAs($this->admin)
            ->get(route('admin.users.index'))
            ->assertStatus(200)
            ->assertSee('Пользователи');
    }

    /** @test */
    public function admin_can_search_users()
    {
        User::factory()->create(['name' => 'Иван Иванов', 'email' => 'ivan@example.com']);

        $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'Иван']))
            ->assertStatus(200)
            ->assertSee('Иван Иванов');
    }

    /** @test */
    public function admin_can_view_single_user()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    /** @test */
    /** @test */
    public function non_admin_cannot_access_admin_pages()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->get(route('admin.tests.index'))
            ->assertStatus(403);
    }
}
