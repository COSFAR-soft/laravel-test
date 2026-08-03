<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Статистика для дашборда
        $stats = [
            'total_tests' => Test::count(),
            'completed_tests' => TestResult::where('user_id', Auth::id())
                ->whereNotNull('completed_at')
                ->count(),
            'average_score' => round(
                TestResult::where('user_id', Auth::id())
                    ->whereNotNull('completed_at')
                    ->avg('score') ?? 0
            ),
        ];

        return view('dashboard', compact('stats'));
    }
}
