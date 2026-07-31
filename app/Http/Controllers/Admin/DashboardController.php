<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Общая статистика
        $totalTests = Test::count();
        $publishedTests = Test::where('is_published', true)->count();
        $totalUsers = User::count();
        $totalAttempts = TestResult::count();
        $completedAttempts = TestResult::whereNotNull('completed_at')->count();

        // Статистика по прохождениям (проценты)
        $allResults = TestResult::whereNotNull('completed_at')->get();
        $avgScore = $allResults->avg(function ($result) {
            return $result->percentage;
        }) ?? 0;


        $passedCount = $allResults->filter(function ($result) {
            return $result->is_passed;
        })->count();

        $failedCount = $completedAttempts - $passedCount;

        // Все тесты
        $allTests = Test::withCount('results')
            ->orderBy('results_count', 'desc')
            ->get();

        // Последние прохождения тестов пользователями
        $recentResults = TestResult::with(['user', 'test'])
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->limit(100)
            ->get();


        return view('admin.dashboard.index', compact(
            'totalTests',
            'publishedTests',
            'totalUsers',
            'totalAttempts',
            'completedAttempts',
            'avgScore',
            'passedCount',
            'failedCount',
            'allTests',
            'recentResults',
        ));
    }

    public function testStats(Test $test)
    {
        $results = TestResult::where('test_id', $test->id)
            ->whereNotNull('completed_at')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => $results->total(),
            'passed' => $results->getCollection()->filter(function ($r) {
                return $r->is_passed;
            })->count(),
            'avg_score' => round($results->avg('score') ?? 0, 1),
            'max_score' => $results->max('score') ?? 0,
            'min_score' => $results->min('score') ?? 0,
        ];

        return view('admin.dashboard.test-stats', compact('test', 'results', 'stats'));
    }

    public function userStats(User $user)
    {
        $results = TestResult::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->with('test')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => $results->total(),
            'passed' => $results->getCollection()->filter(function ($r) {
                return $r->is_passed;
            })->count(),
            'avg_score' => round($results->avg('score') ?? 0, 1),
            'max_score' => $results->max('score') ?? 0,
        ];

        return view('admin.dashboard.user-stats', compact('user', 'results', 'stats'));
    }

    /**
     * Просмотр результата теста конкретного пользователя
     */
    public function viewResult(TestResult $result)
    {
        $test = $result->test;
        $user = $result->user;

        return view('admin.dashboard.result-view', compact('result', 'test', 'user'));
    }
}
