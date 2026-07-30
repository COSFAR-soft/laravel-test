@extends('admin.layouts.admin')

@section('title', 'Управление тестами')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Управление тестами</h2>
        <a href="{{ route('admin.tests.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Создать тест</a>
    </div>

    <div class="row">
        @forelse($tests as $test)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title">{{ $test->title }}</h5>
                            <span class="badge {{ $test->is_published ? 'bg-success' : 'bg-warning' }}">
                            {{ $test->is_published ? 'Опубликован' : 'Черновик' }}
                        </span>
                        </div>
                        <p class="card-text text-muted small">{{ Str::limit($test->description ?? 'Без описания', 100) }}</p>
                        <div class="d-flex gap-2 mb-2">
                            <span class="badge bg-info"><i class="bi bi-list-ol"></i> {{ $test->questions_count }} вопр.</span>
                            <span class="badge bg-secondary"><i class="bi bi-clock"></i> {{ $test->time_limit }} мин</span>
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> {{ $test->passing_score }}%</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent d-flex gap-2">
                        <a href="{{ route('admin.tests.constructor', $test) }}" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-pencil"></i> Конструктор вопросов
                        </a>
                        <a href="{{ route('admin.tests.edit', $test) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-gear"></i>
                        </a>
                        <button onclick="deleteTest({{ $test->id }})" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="bi bi-info-circle display-4 d-block mb-3"></i>
                    <h4>Нет созданных тестов</h4>
                    <p class="text-muted">Создайте свой первый тест!</p>
                    <a href="{{ route('admin.tests.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Создать тест
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">{{ $tests->links() }}</div>

    <script>
        function deleteTest(id) {
            if (!confirm('Удалить этот тест?')) return;
            $.ajax({
                url: '{{ route("admin.tests.destroy", "") }}/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() { location.reload(); },
                error: function() { alert('Ошибка удаления'); }
            });
        }
    </script>
@endsection
