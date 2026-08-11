<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'name', 'email', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $users = $query->paginate(20)->withQueryString();

        $users->each(function ($user) {
            $user->stats = [
                'total_results' => TestResult::where('user_id', $user->id)->count(),
                'completed' => TestResult::where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->count(),
                'avg_score' => round(
                    TestResult::where('user_id', $user->id)
                        ->whereNotNull('completed_at')
                        ->avg('score') ?? 0, 1
                ),
                'passed' => TestResult::where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->where('score', '>=', 70)
                    ->count(),
            ];
        });

        // Сохраняем URL для возврата
        session(['users_index_url' => request()->fullUrl()]);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user, Request $request)
    {
        // Получаем URL для возврата
        $backUrl = $request->query('back') ?? session('users_index_url') ?? route('admin.users.index');

        $results = TestResult::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->with('test')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => TestResult::where('user_id', $user->id)->count(),
            'completed' => TestResult::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->count(),
            'avg_score' => round(
                TestResult::where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->avg('score') ?? 0, 1
            ),
            'passed' => TestResult::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->where('score', '>=', 70)
                ->count(),
            'failed' => TestResult::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->where('score', '<', 70)
                ->count(),
            'best_score' => TestResult::where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->max('score') ?? 0,
            'worst_score' => TestResult::where('user_id', $user->id)
                    ->whereNotNull('completed_at')
                    ->min('score') ?? 0,
        ];

        return view('admin.users.show', compact('user', 'results', 'stats', 'backUrl'));
    }
}
