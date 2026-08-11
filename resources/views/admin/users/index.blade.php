@extends('admin.layouts.admin')

@section('title', 'Пользователи')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Пользователи</h2>
        <span class="text-muted">Всего: {{ $users->total() }}</span>
    </div>

    {{-- Поиск --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Поиск по имени или email..."
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">Найти</button>
                        @if(request('search'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Сбросить</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.users.index', ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-sort-alpha-down"></i> Имя
                        </a>
                        <a href="{{ route('admin.users.index', ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-calendar3"></i> Дата
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Таблица пользователей --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th class="text-center">Тестов пройдено</th>
                        <th class="text-center">Средний балл</th>
                        <th class="text-center">Пройдено</th>
                        <th class="text-center">Дата регистрации</th>
                        <th class="text-center">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td class="text-center">{{ $user->stats['completed'] ?? 0 }}</td>
                            <td class="text-center">
                                <span class="badge {{ ($user->stats['avg_score'] ?? 0) >= 70 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $user->stats['avg_score'] ?? 0 }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $user->stats['passed'] ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ $user->created_at->format('d.m.Y') }}
                                </small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-people fs-2 d-block mb-2"></i>
                                Пользователи не найдены
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Показано {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} из {{ $users->total() }}
                </small>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
