@props([
    'test',
    'userResult' => null
])

<div class="card h-100 shadow-sm hover-shadow transition">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h5 class="card-title mb-0">{{ $test->title }}</h5>
            <span class="badge bg-primary">{{ $test->questions_count }} вопросов</span>
        </div>

        <p class="card-text text-muted small">
            {{ Str::limit($test->description ?? 'Описание отсутствует', 100) }}
        </p>

        <div class="d-flex flex-wrap gap-1 mt-3">
            <span class="badge bg-info">
                <i class="bi bi-clock"></i> {{ $test->time_limit }} мин
            </span>
            <span class="badge bg-success">
                <i class="bi bi-check-circle"></i> {{ $test->passing_score }}%
            </span>
            @if($userResult)
                <span class="badge {{ $userResult->is_passed ? 'bg-success' : 'bg-danger' }}">
                    {{ $userResult->is_passed ? 'Пройден' : 'Не пройден' }}
                </span>
            @endif
        </div>
    </div>
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
        <small class="text-muted">
            <i class="bi bi-people"></i> {{ $test->results->count() }} прошли
        </small>
        <a href="{{ route('tests.show', $test) }}" class="btn btn-primary btn-sm">
            Подробнее →
        </a>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
