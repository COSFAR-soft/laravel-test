<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// АУТЕНТИФИКАЦИЯ
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Неверные учетные данные.'],
        ]);
    }

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
    ]);
});


Route::middleware('auth:sanctum')->group(function () {

    // Получить текущего пользователя
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

// ТЕСТЫ
    Route::get('/tests', function () {
        $tests = \App\Models\Test::where('is_published', true)
            ->withCount('questions')
            ->get()
            ->map(function ($test) {
                return [
                    'id' => $test->id,
                    'title' => $test->title,
                    'description' => $test->description,
                    'time_limit' => $test->time_limit,
                    'passing_score' => $test->passing_score,
                    'questions_count' => $test->questions_count,
                ];
            });

        return response()->json([
            'data' => $tests,
            'meta' => [
                'total' => $tests->count(),
            ],
        ]);
    });

    // Детали теста
    Route::get('/tests/{test}', function (\App\Models\Test $test) {
        $test->load('questions.answers');

        return response()->json([
            'data' => [
                'id' => $test->id,
                'title' => $test->title,
                'description' => $test->description,
                'time_limit' => $test->time_limit,
                'passing_score' => $test->passing_score,
                'questions_count' => $test->questions->count(),
                'questions' => $test->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'type' => $question->type,
                        'points' => $question->points,
                        'answers' => $question->answers->shuffle()->map(function ($answer) {
                            return [
                                'id' => $answer->id,
                                'answer_text' => $answer->answer_text,
                            ];
                        }),
                    ];
                }),
            ],
        ]);
    });

    // Начать тест
    Route::post('/tests/{test}/start', function (\App\Models\Test $test) {
        $user = auth()->user();

        $existing = \App\Models\TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Тест уже начат',
                'error' => 'test_already_started',
            ], 409);
        }

        $result = \App\Models\TestResult::create([
            'user_id' => $user->id,
            'test_id' => $test->id,
            'total_questions' => $test->questions->count(),
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Тест начат',
            'data' => [
                'id' => $result->id,
                'test_id' => $result->test_id,
                'user_id' => $result->user_id,
                'total_questions' => $result->total_questions,
                'score' => $result->score,
                'status' => 'in_progress',
                'started_at' => $result->started_at,
            ],
        ]);
    });

    // Получить вопросы для теста
    Route::get('/tests/{test}/questions', function (\App\Models\Test $test) {
        $user = auth()->user();

        $result = \App\Models\TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->first();

        if (!$result) {
            return response()->json([
                'message' => 'Тест не начат',
                'error' => 'test_not_started',
            ], 404);
        }

        $questions = $test->questions()
            ->with('answers')
            ->orderBy('order')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'type' => $question->type,
                    'points' => $question->points,
                    'answers' => $question->answers->shuffle()->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'answer_text' => $answer->answer_text,
                        ];
                    }),
                ];
            });

        return response()->json(['data' => $questions]);
    });

    // Отправить ответы
    Route::post('/tests/{test}/submit', function (Illuminate\Http\Request $request, \App\Models\Test $test) {
        $user = auth()->user();

        $result = \App\Models\TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->first();

        if (!$result) {
            return response()->json([
                'message' => 'Результат не найден',
                'error' => 'result_not_found',
            ], 404);
        }

        $request->validate([
            'answers' => 'required|array',
        ]);

        $userAnswers = $request->input('answers', []);
        $correctCount = 0;

        foreach ($test->questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;

            if ($question->type === 'single') {
                $correct = $question->answers->where('is_correct', true)->first();
                if ($correct && $userAnswer == $correct->id) {
                    $correctCount++;
                }
            } else {
                $correctIds = $question->answers
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->sort()
                    ->values()
                    ->toArray();

                $userIds = is_array($userAnswer) ? $userAnswer : [];
                sort($userIds);

                if ($correctIds === $userIds) {
                    $correctCount++;
                }
            }
        }

        $score = round(($correctCount / max($result->total_questions, 1)) * 100);

        $result->update([
            'correct_answers' => $correctCount,
            'score' => $score,
            'answers' => $userAnswers,
            'completed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Тест завершен',
            'data' => [
                'id' => $result->id,
                'score' => $result->score,
                'correct_answers' => $result->correct_answers,
                'total_questions' => $result->total_questions,
                'percentage' => $result->percentage,
                'is_passed' => $result->is_passed,
                'completed_at' => $result->completed_at,
            ],
        ]);
    });

    // Получить результаты
    Route::get('/tests/{test}/results', function (\App\Models\Test $test) {
        $user = auth()->user();

        $result = \App\Models\TestResult::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->whereNotNull('completed_at')
            ->latest()
            ->first();

        if (!$result) {
            return response()->json([
                'message' => 'Результаты не найдены',
                'error' => 'results_not_found',
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $result->id,
                'score' => $result->score,
                'correct_answers' => $result->correct_answers,
                'total_questions' => $result->total_questions,
                'percentage' => $result->percentage,
                'is_passed' => $result->is_passed,
                'time_spent' => $result->time_spent,
                'completed_at' => $result->completed_at,
                'test' => [
                    'id' => $test->id,
                    'title' => $test->title,
                    'passing_score' => $test->passing_score,
                ],
            ],
        ]);
    });

    // История
    Route::get('/tests/history', function () {
        $user = auth()->user();

        $results = \App\Models\TestResult::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->with('test')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'data' => $results->getCollection()->map(function ($result) {
                return [
                    'id' => $result->id,
                    'score' => $result->score,
                    'correct_answers' => $result->correct_answers,
                    'total_questions' => $result->total_questions,
                    'percentage' => $result->percentage,
                    'is_passed' => $result->is_passed,
                    'completed_at' => $result->completed_at,
                    'test' => [
                        'id' => $result->test->id,
                        'title' => $result->test->title,
                    ],
                ];
            }),
            'meta' => [
                'total' => $results->total(),
                'passed_count' => $results->getCollection()->filter(function ($result) {
                    return $result->is_passed;
                })->count(),
                'avg_score' => round($results->getCollection()->avg('score') ?? 0, 1),
            ],
        ]);
    });

    // Статистика
    Route::get('/tests/statistics', function () {
        $user = auth()->user();

        $completed = \App\Models\TestResult::where('user_id', $user->id)
            ->whereNotNull('completed_at');

        return response()->json([
            'data' => [
                'total_tests' => [
                    'available' => \App\Models\Test::where('is_published', true)->count(),
                    'completed' => $completed->count(),
                    'in_progress' => \App\Models\TestResult::where('user_id', $user->id)
                        ->whereNull('completed_at')->count(),
                ],
                'scores' => [
                    'average' => round($completed->avg('score') ?? 0, 1),
                    'max' => $completed->max('score') ?? 0,
                    'min' => $completed->min('score') ?? 0,
                ],
                'progress' => [
                    'passed' => $completed->where('score', '>=', 70)->count(),
                    'failed' => $completed->where('score', '<', 70)->count(),
                ],
            ],
        ]);
    });
});
