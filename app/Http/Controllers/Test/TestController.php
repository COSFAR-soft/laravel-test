<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class TestController
 *
 * Публичный контроллер для прохождения тестов пользователями
 * Работа с сессией пользователя
 * подсчет результатов и отображение страниц
 *
 * @package App\Http\Controllers\Test
 */
class TestController extends Controller
{
    /**
     * Список доступных тестов/анкет (опубликованные) для прохождения
     */
    public function index()
    {
        $tests = Test::where('is_published', true)
            ->withCount('questions')
            ->get();

        return view('tests.index', compact('tests'));
    }

    /**
     * Детальная страница теста/анкеты
     * Информация о тесте/анкете и статус пользователя:
     * результат, если уже проходил
     * кнопка "продолжить", если есть незавершенный тест
     */
    public function show(Test $test)
    {
        // Завершенные попытки пользователя
        $result = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNotNull('completed_at')
            ->latest()
            ->first();

        // Незавершенная попытка (если пользователь начал, но не закончил)
        $inProgress = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->first();

        return view('tests.show', compact('test', 'result', 'inProgress'));
    }

    /**
     * Старт теста/анкеты
     * Создаем запись в test_results и перенаправляем на страницу прохождения
     * Если тест/анкета уже начат — перенаправляем на продолжение
     */
    public function start(Test $test)
    {
        // Проверяем, начат ли тест
        $existing = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->first();

        if ($existing) {
            return redirect()->route('tests.take', $test);
        }

        // Создаем новую запись результата
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
     * Страница прохождения теста
     */
    public function take(Test $test)
    {
        $result = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        // Получаем вопросы с ответами, перемешиваем ответы TODO - метка в тесте
        $questions = $test->questions()
            ->with('answers')
            ->orderBy('order')
            ->get()
            ->map(function ($question) {
                $answers = $question->answers->shuffle();
                $question->setRelation('answers', $answers);
                return $question;
            });

        // Считаем оставшееся время
        $timeLimit = $test->time_limit;
        $timeSpent = $result->started_at->diffInMinutes(now());
        $timeLeft = max(0, $timeLimit - $timeSpent);

        // Если время вышло — автоматически завершаем
        if ($timeLeft <= 0) {
            return $this->autoSubmit($test, $result);
        }

        return view('tests.take', compact('test', 'result', 'questions', 'timeLeft'));
    }

    /**
     * Автоматическое завершение теста при истечении времени
     * Используем сохраненные ответы пользователя
     */
    private function autoSubmit(Test $test, TestResult $result)
    {
        $questions = $test->questions()->with('answers')->get();
        $userAnswers = $result->answers ?? [];
        $correctCount = 0;
        $earnedPoints = 0;

        foreach ($questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;

            if ($question->type === 'single') {
                $correct = $question->answers->where('is_correct', true)->first();
                if ($correct && $userAnswer == $correct->id) {
                    $correctCount++;
                    $earnedPoints += $question->points;
                }
            } else {
                // Для множественного выбора сравниваем по тексту (может сломаться по ID)
                $correctTexts = $question->answers
                    ->where('is_correct', true)
                    ->pluck('answer_text')
                    ->sort()
                    ->values()
                    ->toArray();

                if (empty($userAnswer)) {
                    continue;
                }

                if (!is_array($userAnswer)) {
                    $userAnswer = [$userAnswer];
                }

                $userTexts = $question->answers
                    ->whereIn('id', $userAnswer)
                    ->pluck('answer_text')
                    ->sort()
                    ->values()
                    ->toArray();

                if ($correctTexts === $userTexts) {
                    $correctCount++;
                    $earnedPoints += $question->points;
                }
            }
        }

        $result->update([
            'correct_answers' => $correctCount,
            'score' => $earnedPoints,
            'completed_at' => now(),
        ]);

        return redirect()->route('tests.results', $test)
            ->with('warning', 'Время вышло! Тест был автоматически завершен.');
    }

    /**
     * Обработка отправленных ответов
     * Подсчет результатов
     */
    public function submit(Request $request, Test $test)
    {
        $result = TestResult::where('user_id', Auth::id())
            ->where('test_id', $test->id)
            ->whereNull('completed_at')
            ->firstOrFail();

        $userAnswers = $request->input('answers', []);
        $correctCount = 0;
        $earnedPoints = 0;

        foreach ($test->questions as $question) {
            $userAnswer = $userAnswers[$question->id] ?? null;

            if ($question->type === 'single') {
                // Для одиночного выбора проверяем по ID
                $correct = $question->answers->where('is_correct', true)->first();
                if ($correct && $userAnswer == $correct->id) {
                    $correctCount++;
                    $earnedPoints += $question->points;
                }
            } else {
                // Для множественного выбора проверяем по тексту (ломается на ID из-за перемешивания)
                $correctTexts = $question->answers
                    ->where('is_correct', true)
                    ->pluck('answer_text')
                    ->sort()
                    ->values()
                    ->toArray();

                if (empty($userAnswer)) {
                    continue;
                }

                if (!is_array($userAnswer)) {
                    $userAnswer = [$userAnswer];
                }

                $userTexts = $question->answers
                    ->whereIn('id', $userAnswer)
                    ->pluck('answer_text')
                    ->sort()
                    ->values()
                    ->toArray();

                if ($correctTexts === $userTexts) {
                    $correctCount++;
                    $earnedPoints += $question->points;
                }
            }
        }

        // Сохраняем результаты
        $result->update([
            'correct_answers' => $correctCount,
            'score' => $earnedPoints,
            'answers' => $userAnswers,
            'completed_at' => now(),
        ]);

        return redirect()->route('tests.results', $test);
    }

    /**
     * Страница с результатами
     * Вывод ответов
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
     * История всех прохождений пользователя
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
