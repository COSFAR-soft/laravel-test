<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Проверяем, существует ли уже админ
        $admin = User::where('email', 'admin@example.com')->first();

        if (!$admin) {
            User::create([
                'name' => 'Администратор',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $this->command->info('Администратор создан: admin@example.com / password');
        } else {
            $this->command->info('Администратор уже существует');
        }

        // Создаем тестового пользователя для тестов
        $testUser = User::where('email', 'test@example.com')->first();

        if (!$testUser) {
            User::create([
                'name' => 'Тестовый пользователь',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $this->command->info('Тестовый пользователь создан: test@example.com / password');
        }
    }
}
