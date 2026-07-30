@props([
    'test',
    'result' => null,
    'inProgress' => false
])

<div class="card">
    <div class="card-body text-center">
        @if($inProgress)
            <div class="mb-3">
                <i class="bi bi-hourglass-split display-3 text-warning"></i>
                <h5 class="mt-2">{{ __('Тест начат!') }}</h5>
                <p class="text-muted small">{{ __('Продолжите прохождение') }}</p>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('tests.take', $test) }}" class="btn btn-warning">
                    <i class="bi bi-play-fill"></i> {{ __('Продолжить') }}
                </a>
            </div>
        @elseif($result && $result->completed_at)
            <div class="mb-3">
                <div class="display-3 mb-2">
                    {{ $result->percentage }}%
                </div>
                <div>
                    <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }} fs-6 p-2">
                        {{ $result->is_passed ? 'Пройден' : 'Не пройден' }}
                    </span>
                </div>
                <p class="text-muted small mt-2">
                    {{ $result->correct_answers }} / {{ $result->total_questions }}
                </p>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('tests.results', $test) }}" class="btn btn-outline-primary">
                    <i class="bi bi-graph-up"></i> {{ __('Результаты') }}
                </a>
                <a href="{{ route('tests.start', $test) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat"></i> {{ __('Пройти заново') }}
                </a>
            </div>
        @else
            <div class="mb-3">
                <i class="bi bi-file-text display-3 text-primary"></i>
                <h5 class="mt-2">{{ __('Готовы начать?') }}</h5>
                <p class="text-muted small">
                    {{ __('У вас будет') }} {{ $test->time_limit }} {{ __('минут') }}
                </p>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('tests.start', $test) }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-fill"></i> {{ __('Начать тест') }}
                </a>
            </div>
        @endif

        <hr>

        <div class="text-muted small">
            <i class="bi bi-info-circle"></i>
            {{ __('Количество попыток: неограничено') }}
        </div>
    </div>
</div>
