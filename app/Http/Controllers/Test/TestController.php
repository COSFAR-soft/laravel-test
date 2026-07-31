<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    /**
     * Список доступных тестов
     */
    public function index()
    {
        $tests = Test::where('is_published', true)
            ->withCount('questions')
            ->get();

        return view('tests.index', compact('tests'));
    }

    /**
     * Детальная страница теста
     */
    public function show(Test $test)
    {
        // Проверяем, проходил ли пользователь этот тест
        $result = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNotNull('completed_at')
            ->latest()
            ->first();

        // Проверяем, есть ли незавершенный тест
        $inProgress = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->first();

        return view('tests.show', compact('test', 'result', 'inProgress'));
    }

    /**
     * Начать тест
     */
    public function start(Test $test)
    {
        // Проверяем, не начат ли уже тест
        $existing = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->first();

        if ($existing) {
            return redirect()->route('tests.take', $test);
        }

        // Создаем новый результат
        $result = TestResult::create([
            'user_id' => Auth::id(),
            'test_id' => $test->id,
            'total_questions' => $test->questions->count(),
            'correct_answers' => 0,
            'score' => 0,
            'answers' => [],
            'started_at' => now(),
        ]);

        return redirect()->route('tests.take', $test);
    }

    /**
     * Прохождение теста
     */
    public function take(Test $test)
    {
        $result = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        $questions = $test->questions()
            ->with('answers')
            ->orderBy('order')
            ->get()
            ->map(function ($question) {
                // Перемешиваем ответы для каждого вопроса
                $answers = $question->answers->shuffle();
                $question->setRelation('answers', $answers);
                return $question;
            });

        // Проверяем время
        $timeLimit = $test->time_limit;
        $timeSpent = $result->started_at->diffInMinutes(now());
        $timeLeft = max(0, $timeLimit - $timeSpent);

        if ($timeLeft <= 0) {
            return $this->autoSubmit($test, $result);
        }

        return view('tests.take', compact('test', 'result', 'questions', 'timeLeft'));
    }

    /**
     * Автоматическая отправка при истечении времени
     */
    private function autoSubmit(Test $test, TestResult $result)
    {
        // Получаем все вопросы
        $questions = $test->questions()->with('answers')->get();
        $correctCount = 0;

        // Проверяем сохраненные ответы
        $userAnswers = $result->answers ?? [];

        foreach ($questions as $question) {
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

        $score = round(($correctCount / $result->total_questions) * 100);

        $result->update([
            'correct_answers' => $correctCount,
            'score' => $score,
            'completed_at' => now(),
        ]);

        return redirect()->route('tests.results', $test)
            ->with('warning', 'Время вышло! Тест был автоматически завершен.');
    }

    /**
     * Отправить ответы
     */
    public function submit(Request $request, Test $test)
    {
        $result = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        $userAnswers = $request->input('answers', []);
        $correctCount = 0;

        foreach ($test->questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;

            if ($question->type === 'single') {
                // Одиночный выбор
                $correct = $question->answers->where('is_correct', true)->first();
                if ($correct && $userAnswer == $correct->id) {
                    $correctCount++;
                }
            } else {
                // Множественный выбор
                $correctIds = $question->answers
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->sort()
                    ->values()
                    ->toArray();

                // Если пользователь не выбрал ничего
                if (is_null($userAnswer) || $userAnswer === '' || (is_array($userAnswer) && empty($userAnswer))) {
                    continue;
                }

                // Приводим к массиву
                if (!is_array($userAnswer)) {
                    $userAnswer = [$userAnswer];
                }

                $userIds = array_map('intval', $userAnswer);
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

        return redirect()->route('tests.results', $test);
    }

    /**
     * Результаты теста
     */
    public function results(Test $test)
    {
        $result = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNotNull('completed_at')
            ->latest()
            ->firstOrFail();

        return view('tests.results', compact('test', 'result'));
    }

    /**
     * История прохождений
     */
    public function history()
    {
        $results = TestResult::where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->with('test')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => $results->total(),
            'passed' => $results->filter(function ($result) {
                return $result->is_passed;
            })->count(),
            'avg_score' => $results->avg('score') ?? 0,
        ];

        return view('tests.history', compact('results', 'stats'));
    }
}
