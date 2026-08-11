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

    /** @test - админ видит дашборд */
    public function admin_can_view_dashboard()
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(200)
            ->assertSee('Статистика');
    }

    /** @test - дашборд показывает правильную статистику */
    public function dashboard_shows_correct_statistics()
    {
        Test::factory()->count(3)->create(['is_published' => true]);
        Test::factory()->count(1)->create(['is_published' => false]);

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
            ->assertSee('3')
            ->assertSee('5')
            ->assertSee('80%');
    }

    /** @test - админ смотрит статистику по тесту */
    public function admin_can_view_test_statistics()
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

    /** @test - админ смотрит детали результата */
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

    /** @test - дашборд без результатов */
    public function dashboard_handles_no_results_gracefully()
    {
        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(200)
            ->assertSee('0')
            ->assertSee('0%');
    }

    /** @test - статистика теста без результатов */
    public function test_stats_handles_no_results()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.dashboard.test-stats', $test))
            ->assertStatus(200)
            ->assertSee('Всего прохождений')
            ->assertSee('0');
    }

    /** @test - обычный юзер не попадает в админку */
    public function non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)
            ->get(route('admin.dashboard.index'))
            ->assertStatus(403);
    }
}
