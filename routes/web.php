<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\DashboardController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


RateLimiter::for('admin-questions', function ($job) {
    return Limit::perMinute(60);
});

Route::prefix('admin')->middleware(['auth', 'admin', 'throttle:admin-questions'])->group(function () {
    Route::resource('questions', Admin\QuestionController::class);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// МАРШРУТЫ ДЛЯ ТЕСТОВ
Route::middleware(['auth'])->prefix('tests')->name('tests.')->group(function () {
    // Список всех тестов
    Route::get('/', [TestController::class, 'index'])->name('index');
    // Страница теста
    Route::get('/{test}', [TestController::class, 'show'])->name('show');
    // Начать тест
    Route::get('/{test}/start', [TestController::class, 'start'])->name('start');
    // Прохождение теста
    Route::get('/{test}/take', [TestController::class, 'take'])->name('take');
    // Отправить ответы
    Route::post('/{test}/submit', [TestController::class, 'submit'])->name('submit');
    // Результаты
    Route::get('/{test}/results', [TestController::class, 'results'])->name('results');
});

// История прохождений
Route::middleware(['auth'])->get('/history', [TestController::class, 'history'])
    ->name('tests.history');

require __DIR__.'/auth.php';

require __DIR__.'/admin.php';
