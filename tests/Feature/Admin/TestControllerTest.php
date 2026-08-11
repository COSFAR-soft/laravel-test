<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestControllerTest extends TestCase
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

    /** @test - админ видит список тестов */
    public function admin_can_view_tests_list()
    {
        Test::factory()->count(5)->create();

        $this->actingAs($this->admin)
            ->get(route('admin.tests.index'))
            ->assertStatus(200)
            ->assertSee('Тесты');
    }

    /** @test - админ видит форму создания теста */
    public function admin_can_view_create_test_page()
    {
        $this->actingAs($this->admin)
            ->get(route('admin.tests.create'))
            ->assertStatus(200)
            ->assertSee('Создать новый тест')
            ->assertSee('Название теста')
            ->assertSee('Время (минуты)')
            ->assertSee('Проходной балл (%)');
    }

    /** @test - админ создает новый тест */
    public function admin_can_create_new_test()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.tests.store'), [
                'title' => 'Новый тест',
                'description' => 'Описание нового теста',
                'time_limit' => 30,
                'passing_score' => 70,
                'is_published' => true,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tests', [
            'title' => 'Новый тест',
            'description' => 'Описание нового теста',
            'time_limit' => 30,
            'passing_score' => 70,
            'is_published' => true,
        ]);
    }

    /** @test - админ создает тест без публикации */
    public function admin_can_create_test_without_publishing()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.tests.store'), [
                'title' => 'Черновик теста',
                'description' => 'Описание черновика',
                'time_limit' => 15,
                'passing_score' => 50,
                'is_published' => false,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tests', [
            'title' => 'Черновик теста',
            'is_published' => false,
        ]);
    }

    /** @test - админ не создает тест с кривыми данными */
    public function admin_cannot_create_test_with_invalid_data()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.tests.store'), [
                'title' => '',
                'description' => 'Описание',
                'time_limit' => 999,
                'passing_score' => 150,
            ])
            ->assertSessionHasErrors(['title', 'time_limit', 'passing_score']);
    }

    /** @test - админ создает тест */
    public function admin_can_create_test_with_minimum_requirements()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.tests.store'), [
                'title' => 'тест',
                'description' => null,
                'time_limit' => 1,
                'passing_score' => 0,
                'is_published' => false,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tests', [
            'title' => 'тест',
            'description' => null,
            'time_limit' => 1,
            'passing_score' => 0,
        ]);
    }

    /** @test - админ видит форму редактирования */
    public function admin_can_edit_test()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.tests.edit', $test))
            ->assertStatus(200)
            ->assertSee($test->title);
    }

    /** @test - админ обновляет тест */
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
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tests', [
            'id' => $test->id,
            'title' => 'Новое название',
        ]);
    }

    /** @test - админ удаляет тест */
    public function admin_can_delete_test()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.tests.destroy', $test))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('tests', ['id' => $test->id]);
    }

    /** @test - админ заходит в конструктор */
    public function admin_can_access_constructor()
    {
        $test = Test::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.tests.constructor', $test))
            ->assertStatus(200)
            ->assertSee('Конструктор');
    }

    /** @test - обычный юзер не попадает в управление тестами */
    public function non_admin_cannot_access_test_management()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->actingAs($user)
            ->get(route('admin.tests.index'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->get(route('admin.tests.create'))
            ->assertStatus(403);
    }
}
