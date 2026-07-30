<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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
