@extends('admin.layouts.admin')

@section('title', 'Статистика')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Статистика</h2>
        <span class="text-muted">Обновлено: {{ now()->format('d.m.Y H:i') }}</span>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3 d-flex">
            <div class="card bg-primary text-white w-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="mb-1">Всего тестов</h6>
                        <h2 class="mb-0">{{ $totalTests }}</h2>
                        <small>Опубликовано: {{ $publishedTests }}</small>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-file-text fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card bg-success text-white w-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="mb-1">Пользователей</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card bg-info text-white w-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="mb-1">Прохождений</h6>
                        <h2 class="mb-0">{{ $completedAttempts }}</h2>
                        <small>Всего попыток: {{ $totalAttempts }}</small>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-check2-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card bg-warning text-white w-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="mb-1">Средний процент</h6>
                        <h2 class="mb-0">{{ round($avgScore) }}%</h2>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-graph-up fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

    </div>

    <div class="row g-4">
        <div class="col-md-6 d-flex">
            <div class="card h-100 w-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-list-ul"></i> Список тестов</h6>
                    <span class="badge bg-primary">{{ $allTests->count() }}</span>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @forelse($allTests as $test)
                            <a href="{{ route('admin.dashboard.test-stats', $test) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi bi-file-text me-2"></i>
                                        {{ Str::limit($test->title, 40) }}
                                    </div>
                                    <div>
                                        <span class="badge bg-info">{{ $test->results_count }} прохождений</span>
                                        {!! $test->is_published ? '' : '<span class="badge bg-secondary">Черновик </span>' !!}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-3 text-center text-muted">Нет тестов</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex">
            <div class="card h-100 w-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Последние прохождения</h6>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @forelse($recentResults as $result)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('admin.dashboard.user-stats', $result->user_id) }}" class="text-decoration-none">
                                            <i class="bi bi-person-circle me-2"></i>
                                            {{ $result->user->name ?? 'Неизвестно' }}
                                        </a>
                                        <span class="badge bg-secondary ms-2">{{ Str::limit($result->test->title ?? 'Удален', 30) }}</span>
                                    </div>
                                    <div>
                                        <span class="badge bg-info">{{ $result->score }} баллов</span>
                                        <span class="badge bg-primary">{{ round($result->percentage) }}%</span>
                                        <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }}">
                                            {{ $result->is_passed ? 'Пройден' : 'Не пройден' }}
                                        </span>
                                        <small class="text-muted ms-2">{{ $result->completed_at ? $result->completed_at->format('d.m.Y H:i') : '-' }}</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted">Нет прохождений</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush
