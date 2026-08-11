<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllersTest extends TestCase
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

    /** @test - админ видит список юзеров */
    public function admin_can_view_users_list()
    {
        User::factory()->count(5)->create();

        $this->actingAs($this->admin)
            ->get(route('admin.users.index'))
            ->assertStatus(200)
            ->assertSee('Пользователи');
    }

    /** @test - админ ищет юзера по имени */
    public function admin_can_search_users_by_name()
    {
        User::factory()->create(['name' => 'Иван Иванов']);

        $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'Иван']))
            ->assertStatus(200)
            ->assertSee('Иван Иванов');
    }

    /** @test - админ ищет юзера по email */
    public function admin_can_search_users_by_email()
    {
        User::factory()->create(['email' => 'ivan@example.com']);

        $this->actingAs($this->admin)
            ->get(route('admin.users.index', ['search' => 'ivan@']))
            ->assertStatus(200)
            ->assertSee('ivan@example.com');
    }

    /** @test - админ смотрит профиль юзера */
    public function admin_can_view_single_user()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee($user->email);
    }

    /** @test - админ видит статистику юзера */
    public function admin_can_view_user_statistics()
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.users.show', $user))
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee('Всего прохождений');
    }

    /** @test - обычный юзер не попадает в управление юзерами */
    public function non_admin_cannot_access_user_management()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $targetUser = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->get(route('admin.users.show', $targetUser))
            ->assertStatus(403);
    }
}
