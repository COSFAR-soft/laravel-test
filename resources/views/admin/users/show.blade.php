@extends('admin.layouts.admin')

@section('title', 'Пользователь: ' . $user->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Пользователь</h2>
            <p class="text-muted mb-0">
                <a href="{{ $backUrl ?? route('admin.users.index') }}" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Назад
                </a>
            </p>
        </div>
    </div>

    {{-- Карточка пользователя --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3 d-flex">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle fs-1 me-3 text-primary"></i>
                        <div>
                            <h5 class="mb-0">{{ $user->name }}</h5>
                            <small class="text-muted">{{ $user->email }}</small>
                            <div class="mt-1">
                                <span class="badge bg-secondary">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $user->created_at->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card bg-primary text-white w-100">
                <div class="card-body">
                    <h6 class="mb-1">Всего прохождений</h6>
                    <h2 class="mb-0">{{ $stats['total'] }}</h2>
                    <small>Завершено: {{ $stats['completed'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card bg-success text-white w-100">
                <div class="card-body">
                    <h6 class="mb-1">Средний балл</h6>
                    <h2 class="mb-0">{{ $stats['avg_score'] }}%</h2>
                    <small>Пройдено: {{ $stats['passed'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-flex">
            <div class="card bg-info text-white w-100">
                <div class="card-body">
                    <h6 class="mb-1">Результаты</h6>
                    <h2 class="mb-0">{{ $stats['best_score'] }}%</h2>
                    <small>Лучший / Худший: {{ $stats['worst_score'] }}%</small>
                </div>
            </div>
        </div>
    </div>

    {{-- История прохождений --}}
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-clock-history"></i> История прохождений</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Тест</th>
                        <th class="text-center">Баллы</th>
                        <th class="text-center">Правильных</th>
                        <th class="text-center">Статус</th>
                        <th class="text-center">Время</th>
                        <th class="text-center">Дата</th>
                        <th class="text-center">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($results as $result)
                        <tr>
                            <td>
                                <strong>{{ $result->test->title ?? 'Удаленный тест' }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold">{{ $result->score }}%</span>
                            </td>
                            <td class="text-center">
                                {{ $result->correct_answers }} / {{ $result->total_questions }}
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }}">
                                    {{ $result->is_passed ? 'Пройден' : 'Не пройден' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <small>{{ $result->time_spent ?? '-' }} мин</small>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ $result->completed_at ? $result->completed_at->format('d.m.Y H:i') : '-' }}
                                </small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.result.view', $result) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                                Нет прохождений
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($results->hasPages())
            <div class="card-footer">
                {{ $results->links() }}
            </div>
        @endif
    </div>
@endsection
