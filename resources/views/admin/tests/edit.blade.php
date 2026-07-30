@extends('admin.layouts.admin')

@section('title', 'Редактировать тест')

@section('content')
    <div class="card">
        <div class="card-header"><h5>Редактировать тест: {{ $test->title }}</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.tests.update', $test) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Название теста <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $test->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Описание</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $test->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="time_limit" class="form-label">Время (минуты) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('time_limit') is-invalid @enderror" id="time_limit" name="time_limit" value="{{ old('time_limit', $test->time_limit) }}" min="1" required>
                        @error('time_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="passing_score" class="form-label">Проходной балл (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('passing_score') is-invalid @enderror" id="passing_score" name="passing_score" value="{{ old('passing_score', $test->passing_score) }}" min="0" max="100" required>
                        @error('passing_score')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ old('is_published', $test->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published"><i class="bi bi-globe"></i> Опубликовать</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Сохранить</button>
                    <a href="{{ route('admin.tests.index') }}" class="btn btn-secondary"><i class="bi bi-x-lg"></i> Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection
