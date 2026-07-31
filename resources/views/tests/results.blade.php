<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>{{ __('Результаты') }}: {{ $test->title }}</span>
            <a href="{{ route('tests.show', $test) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body text-center py-5">

                    {{-- Процент баллов --}}
                    <div class="display-3 fw-bold mb-2">
                        {{ $result->score_percentage }}%
                    </div>
                    <div class="text-muted mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        {{ $result->score }} / {{ $test->questions->sum('points') }} баллов
                    </div>

                    {{-- Статус --}}
                    <div class="mb-4">
                        <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }} fs-5 p-2">
                            {{ $result->is_passed ? 'Тест пройден!' : 'Тест не пройден' }}
                        </span>
                        @if(!$result->is_passed)
                            <div class="mt-2 text-muted small">
                                Проходной балл: {{ $test->passing_score }}%
                            </div>
                        @endif
                    </div>

                    {{-- Статистика --}}
                    <div class="row g-3 mt-4">
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Правильных ответов') }}</div>
                                <div class="h4 mb-0 text-success">{{ $result->correct_answers }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Всего вопросов') }}</div>
                                <div class="h4 mb-0">{{ $result->total_questions }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Заработано баллов') }}</div>
                                <div class="h4 mb-0 text-primary">{{ $result->score }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Затраченное время') }}</div>
                                <div class="h4 mb-0">{{ $result->time_spent ?? 'N/A' }} мин</div>
                            </div>
                        </div>
                    </div>

                    {{-- Детали ответов --}}
                    @if($result->answers)
                        <div class="mt-4 text-start">
                            <h6 class="mb-3">Детали ответов:</h6>
                            <div class="bg-light p-3 rounded" style="font-size: 13px; max-height: 400px; overflow: auto;">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">№</th>
                                        <th>Вопрос</th>
                                        <th style="width: 70px;">Баллы</th>
                                        <th style="width: 100px;">Ваш ответ</th>
                                        <th style="width: 100px;">Правильный</th>
                                        <th style="width: 50px;">Статус</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $totalEarned = 0; @endphp
                                    @foreach($test->questions as $q)
                                        @php
                                            // Получаем правильные ID и тексты
                                            $correctIds = $q->answers->where('is_correct', true)->pluck('id')->sort()->values()->toArray();
                                            $correctTexts = $q->answers->where('is_correct', true)->pluck('answer_text')->sort()->values()->toArray();

                                            // Получаем ответ пользователя
                                            $userAnswer = $result->answers[$q->id] ?? null;

                                            // Приводим к массиву
                                            if (!is_array($userAnswer) && !is_null($userAnswer)) {
                                                $userAnswer = [$userAnswer];
                                            }
                                            $userIds = is_array($userAnswer) ? $userAnswer : [];
                                            sort($userIds);

                                            // Получаем тексты ответов пользователя
                                            $userTexts = [];
                                            if (!empty($userIds)) {
                                                $userTexts = $q->answers->whereIn('id', $userIds)->pluck('answer_text')->sort()->values()->toArray();
                                            }

                                            $isCorrect = $correctTexts === $userTexts;

                                            if ($isCorrect) {
                                                $totalEarned += $q->points;
                                            }

                                            // Формируем тексты для отображения
                                            $correctText = implode(', ', $correctTexts);
                                            $userText = !empty($userTexts) ? implode(', ', $userTexts) : '(не выбран)';
                                        @endphp
                                        <tr class="{{ $isCorrect ? 'table-success' : 'table-danger' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-start">{{ $q->question_text }}</td>
                                            <td class="text-center">{{ $q->points }}</td>
                                            <td class="text-start">{{ $userText }}</td>
                                            <td class="text-start">{{ $correctText }}</td>
                                            <td class="text-center">
                                                {!! $isCorrect ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>' !!}
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                    <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="2" class="text-end">Итого:</td>
                                        <td class="text-center">{{ $test->questions->sum('points') }}</td>
                                        <td colspan="2" class="text-center">{{ $totalEarned }} баллов</td>
                                        <td class="text-center">{{ round(($totalEarned / max($test->questions->sum('points'), 1)) * 100) }}%</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif
                    <hr class="my-4">
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('tests.show', $test) }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> {{ __('К тесту') }}
                        </a>
                        <a href="{{ route('tests.start', $test) }}" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> {{ __('Пройти заново') }}
                        </a>
                        <a href="{{ route('tests.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> {{ __('Все тесты') }}
                        </a>
                        <a href="{{ route('tests.history') }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history"></i> {{ __('История') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
