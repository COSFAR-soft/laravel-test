<x-app-layout>
    <x-slot name="header">
        {{ __('История тестирования') }}
    </x-slot>

    <!-- Статистика -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <div class="display-6">{{ $stats['total'] }}</div>
                    <p class="mb-0">{{ __('Всего тестов') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="display-6">{{ $stats['passed'] }}</div>
                    <p class="mb-0">{{ __('Пройдено') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <div class="display-6">{{ $stats['total'] - $stats['passed'] }}</div>
                    <p class="mb-0">{{ __('Не пройдено') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <div class="display-6">{{ round($stats['avg_score']) }}%</div>
                    <p class="mb-0">{{ __('Средний балл') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Список результатов -->
    @forelse($results as $result)
        <div class="card mb-3 hover-shadow transition">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="card-title mb-0">{{ $result->test->title }}</h5>
                        <small class="text-muted">
                            <i class="bi bi-calendar3"></i>
                            {{ $result->completed_at->format('d.m.Y H:i') }}
                        </small>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-secondary fs-6">{{ $result->score }}%</span>
                            <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }} fs-6">
                                {{ $result->is_passed ? 'Пройден' : 'Не пройден' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">
                            <i class="bi bi-check-circle"></i> {{ $result->correct_answers }} / {{ $result->total_questions }}
                            @if($result->time_spent)
                                <br><i class="bi bi-clock"></i> {{ $result->time_spent }} мин
                            @endif
                        </small>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('tests.results', $result->test) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> {{ __('Подробнее') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-clock-history display-4 d-block mb-3"></i>
            <h4>{{ __('История пуста') }}</h4>
            <p class="text-muted">{{ __('Пройдите свой первый тест!') }}</p>
            <a href="{{ route('tests.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-list"></i> {{ __('К тестам') }}
            </a>
        </div>
    @endforelse

    <!-- Пагинация -->
    @if($results->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $results->links() }}
        </div>
    @endif
</x-app-layout>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateX(4px);
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
