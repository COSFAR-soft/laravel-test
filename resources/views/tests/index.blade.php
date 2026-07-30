<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>{{ __('Доступные тесты по Laravel') }}</span>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($tests as $test)
            <div class="col-md-4 mb-4">
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
                            @php
                                $userResult = $test->results->where('user_id', auth()->id())->whereNotNull('completed_at')->first();
                            @endphp
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
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-info-circle display-4 d-block mb-3"></i>
                    <h4>{{ __('Пока нет доступных тестов') }}</h4>
                    <p class="text-muted">{{ __('Загляните позже!') }}</p>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
