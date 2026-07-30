<?php

namespace Tests\Feature\Test;

use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestPageTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $test;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->test = Test::create([
            'title' => 'Тест по Laravel',
            'description' => 'Проверка знаний Laravel',
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);

        $question = Question::create([
            'test_id' => $this->test->id,
            'question_text' => 'Что такое Laravel?',
            'type' => 'single',
            'points' => 1,
            'order' => 1,
        ]);

        Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'PHP фреймворк',
            'is_correct' => true,
        ]);
        Answer::create([
            'question_id' => $question->id,
            'answer_text' => 'JavaScript фреймворк',
            'is_correct' => false,
        ]);
    }

    /** @test */
    public function test_page_displays_available_tests()
    {
        $response = $this->actingAs($this->user)
            ->get(route('tests.index'));

        $response->assertStatus(200)
            ->assertSee($this->test->title);
    }

    /** @test */
    public function test_page_shows_guest_message_when_no_tests()
    {
        $this->test->delete();

        $response = $this->actingAs($this->user)
            ->get(route('tests.index'));

        $response->assertStatus(200)
            ->assertSee('Нет доступных тестов');
    }

    /** @test */
    public function test_show_page_displays_test_details()
    {
        $response = $this->actingAs($this->user)
            ->get(route('tests.show', $this->test));

        $response->assertStatus(200)
            ->assertSee($this->test->title)
            ->assertSee('30 минут')
            ->assertSee('70%');
    }

    /** @test */
    public function test_start_starts_test_and_creates_result()
    {
        $response = $this->actingAs($this->user)
            ->get(route('tests.start', $this->test));

        $response->assertRedirect(route('tests.take', $this->test));

        $this->assertDatabaseHas('test_results', [
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
        ]);
    }

    /** @test */
    public function test_take_page_shows_questions()
    {
        TestResult::create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('tests.take', $this->test));

        $response->assertStatus(200)
            ->assertSee('Вопрос 1')
            ->assertSee('Что такое Laravel?')
            ->assertSee('PHP фреймворк');
    }

    /** @test */
    public function test_take_page_redirects_if_no_result()
    {
        $response = $this->actingAs($this->user)
            ->get(route('tests.take', $this->test));

        $response->assertStatus(404);
    }

    /** @test */
    public function test_submit_calculates_score_correctly()
    {
        $result = TestResult::create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now()->subMinutes(5),
        ]);

        $question = $this->test->questions->first();
        $correctAnswer = $question->answers->where('is_correct', true)->first();

        $response = $this->actingAs($this->user)
            ->post(route('tests.submit', $this->test), [
                'answers' => [
                    $question->id => $correctAnswer->id,
                ],
            ]);

        $response->assertRedirect(route('tests.results', $this->test));

        $this->assertDatabaseHas('test_results', [
            'id' => $result->id,
            'correct_answers' => 1,
            'score' => 100,
        ]);
    }

    /** @test */
    public function test_results_page_shows_result()
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

        $response = $this->actingAs($this->user)
            ->get(route('tests.results', $this->test));

        $response->assertStatus(200)
            ->assertSee('100%')
            ->assertSee('Тест пройден!');
    }

    /** @test */
    public function test_history_page_displays_user_results()
    {
        TestResult::create([
            'user_id' => $this->user->id,
            'test_id' => $this->test->id,
            'total_questions' => 1,
            'correct_answers' => 1,
            'score' => 100,
            'answers' => [],
            'started_at' => now()->subDays(1),
            'completed_at' => now()->subDays(1),
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('tests.history'));

        $response->assertStatus(200)
            ->assertSee('История тестирования')
            ->assertSee('Тест по Laravel')
            ->assertSee('100%');
    }

    /** @test */
    public function unauthorized_users_cannot_access_test_pages()
    {
        $response = $this->get(route('tests.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('tests.show', $this->test));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('tests.start', $this->test));
        $response->assertRedirect(route('login'));
    }
}
