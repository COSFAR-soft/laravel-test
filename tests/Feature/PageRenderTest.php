<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PageRenderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->test = Test::create([
            'title' => 'Тест для проверки',
            'description' => 'Описание теста',
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $question = Question::create([
            'test_id' => $this->test->id,
            'question_text' => 'Тестовый вопрос?',
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

    /** @test - главная страница доступна */
    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test - страница логина доступна */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Вход');
    }

    /** @test - страница регистрации доступна */
    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Регистрация');
    }

    /** @test - страница восстановления пароля доступна */
    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertSee('Забыли пароль?');
    }

    /** @test - гость редиректится с дашборда на логин */
    public function test_dashboard_redirects_guest_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /** @test - гость редиректится со страницы тестов на логин */
    public function test_tests_redirects_guest_to_login(): void
    {
        $response = $this->get(route('tests.index'));
        $response->assertRedirect('/login');
    }

    /** @test - гость редиректится со страницы истории на логин */
    public function test_history_redirects_guest_to_login(): void
    {
        $response = $this->get(route('tests.history'));
        $response->assertRedirect('/login');
    }

    /** @test - авторизованный юзер видит дашборд */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Статистика');
    }

    /** @test - авторизованный юзер видит список тестов */
    public function test_authenticated_user_can_access_tests_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('tests.index'));
        $response->assertStatus(200);
        $response->assertSee('Доступные тесты по Laravel');
        $response->assertSee($this->test->title);
    }

    /** @test - авторизованный юзер видит историю */
    public function test_authenticated_user_can_access_history_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('tests.history'));
        $response->assertStatus(200);
        $response->assertSee('История тестирования');
    }

    /** @test - авторизованный юзер видит детали теста */
    public function test_authenticated_user_can_access_test_show_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('tests.show', $this->test));
        $response->assertStatus(200);
        $response->assertSee($this->test->title);
        $response->assertSee('30 минут');
        $response->assertSee('70%');
    }

    /** @test - авторизованный юзер видит страницу прохождения теста */
    public function test_authenticated_user_can_access_test_take_page(): void
    {
        // Создаём результат (начатый тест)
        TestResult::create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->get(route('tests.take', $this->test));
        $response->assertStatus(200);
        $response->assertSee('Вопрос 1');
        $response->assertSee('Тестовый вопрос?');
    }

    /** @test - авторизованный юзер видит страницу результатов */
    public function test_authenticated_user_can_access_results_page(): void
    {
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

        $response = $this->actingAs($this->user)->get(route('tests.results', $this->test));
        $response->assertStatus(200);
        $response->assertSee('100%');
        $response->assertSee('Тест пройден!');
    }

    /** @test - админ видит панель управления */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard.index'));
        $response->assertStatus(200);
        $response->assertSee('Статистика');
    }

    /** @test - админ видит список тестов в админке */
    public function test_admin_can_access_admin_tests_list(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.tests.index'));
        $response->assertStatus(200);
        $response->assertSee('Тесты');
    }

    /** @test - админ видит форму создания теста */
    public function test_admin_can_access_admin_test_create_page(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.tests.create'));
        $response->assertStatus(200);
        $response->assertSee('Создать новый тест');
        $response->assertSee('Название теста');
    }

    /** @test - админ видит конструктор теста */
    public function test_admin_can_access_admin_test_constructor(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.tests.constructor', $this->test));
        $response->assertStatus(200);
        $response->assertSee('Конструктор');
        $response->assertSee($this->test->title);
    }

    /** @test - админ видит список пользователей */
    public function test_admin_can_access_admin_users_list(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Пользователи');
    }

    /** @test - админ видит профиль пользователя */
    public function test_admin_can_access_admin_user_show_page(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.users.show', $this->user));
        $response->assertStatus(200);
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->email);
    }

    /** @test - обычный юзер не может зайти в админку */
    public function test_non_admin_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->get(route('admin.tests.index'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertStatus(403);
    }
}
