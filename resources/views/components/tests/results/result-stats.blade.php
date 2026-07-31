@props([
    'result',
    'test'
])

<div class="text-center">
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
                <div class="small text-muted">Правильных ответов</div>
                <div class="h4 mb-0 text-success">{{ $result->correct_answers }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-3 bg-light rounded">
                <div class="small text-muted">Всего вопросов</div>
                <div class="h4 mb-0">{{ $result->total_questions }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-3 bg-light rounded">
                <div class="small text-muted">Заработано баллов</div>
                <div class="h4 mb-0 text-primary">{{ $result->score }}</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-3 bg-light rounded">
                <div class="small text-muted">Затраченное время</div>
                <div class="h4 mb-0">{{ $result->time_spent ?? 'N/A' }} мин</div>
            </div>
        </div>
    </div>
</div>
