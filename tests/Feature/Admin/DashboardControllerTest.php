<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
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
    public function admin_can_view_test_stats()
    {
        $test = Test::factory()->create();
        $user = User::factory()->create();

        TestResult::factory()->count(3)->create([
            'test_id' => $test->id,
            'user_id' => $user->id,
            'completed_at' => now(),
            'score' => 80,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.test-stats', $test))
            ->assertStatus(200)
            ->assertSee($test->title)
            ->assertSee('80%');
    }

    /** @test */
    public function admin_can_view_user_stats()
    {
        $user = User::factory()->create();
        $test = Test::factory()->create();

        TestResult::factory()->count(2)->create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'completed_at' => now(),
            'score' => 90,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee('90%');
    }

    /** @test */
    public function admin_can_view_result_details()
    {
        $user = User::factory()->create(['name' => 'Test User']);

        $test = Test::factory()->create();

        $result = TestResult::factory()->create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'total_questions' => 20,
            'correct_answers' => 17,
            'score' => 85,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.result.view', $result));

        $response->assertStatus(200);
        $response->assertSee($result->score . '%');
    }

    /** @test */
    public function dashboard_handles_no_results_gracefully()
    {
        // Нет результатов в БД
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(200)
            ->assertSee('0')
            ->assertSee('0%');
    }

    /** @test */
    public function dashboard_test_stats_handles_no_results()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.test-stats', $test))
            ->assertStatus(200)
            ->assertSee('Всего прохождений')
            ->assertSee('0');
    }


    /** @test */
    public function non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(403);
    }
}
