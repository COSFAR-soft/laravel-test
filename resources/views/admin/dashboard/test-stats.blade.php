@extends('admin.layouts.admin')

@section('title', 'Статистика по тесту')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>{{ $test->title }}</h2>
            <p class="text-muted mb-0">Статистика прохождений теста</p>
        </div>
        <a href="{{ route('admin.dashboard.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Назад
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['total'] }}</h3>
                    <p class="mb-0">Всего прохождений</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['passed'] }}</h3>
                    <p class="mb-0">Пройдено</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ round($stats['avg_score']) }}%</h3>
                    <p class="mb-0">Средний балл</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ round($stats['max_score']) }}%</h3>
                    <p class="mb-0">Максимальный балл</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-list"></i> Все прохождения</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Правильных</th>
                        <th>Всего</th>
                        <th>Баллы</th>
                        <th>Результат</th>
                        <th>Время</th>
                        <th>Дата</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($results as $result)
                        <tr>
                            <td>{{ $result->user->name ?? 'Неизвестно' }}</td>
                            <td>{{ $result->correct_answers }}</td>
                            <td>{{ $result->total_questions }}</td>
                            <td>{{ $result->score }}%</td>
                            <td>
                                <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }}">
                                    {{ $result->is_passed ? 'Пройден' : 'Не пройден' }}
                                </span>
                            </td>
                            <td>{{ $result->time_spent ?? '-' }} мин</td>
                            <td>{{ $result->completed_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Нет прохождений</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $results->links() }}
        </div>
    </div>
@endsection
