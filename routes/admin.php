<?php

use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Статистика
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/test/{test}', [DashboardController::class, 'testStats'])->name('dashboard.test-stats');
    Route::get('/dashboard/user/{user}', [DashboardController::class, 'userStats'])->name('dashboard.user-stats');
    Route::get('/result/{result}', [DashboardController::class, 'viewResult'])->name('result.view');

    // ТЕСТЫ
    Route::get('/tests', [TestController::class, 'index'])->name('tests.index');
    Route::get('/tests/create', [TestController::class, 'create'])->name('tests.create');
    Route::post('/tests', [TestController::class, 'store'])->name('tests.store');
    Route::get('/tests/{test}/edit', [TestController::class, 'edit'])->name('tests.edit');
    Route::put('/tests/{test}', [TestController::class, 'update'])->name('tests.update');
    Route::delete('/tests/{test}', [TestController::class, 'destroy'])->name('tests.destroy');
    Route::get('/tests/{test}/constructor', [TestController::class, 'constructor'])->name('tests.constructor');

    // ВОПРОСЫ
    Route::get('/tests/{test}/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/tests/{test}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('/questions/reorder', [QuestionController::class, 'reorder'])->name('questions.reorder');
    Route::post('/questions/partial', [QuestionController::class, 'partial'])->name('questions.partial');
});
