@props(['question' => null, 'answers' => [], 'index' => 0])

<div class="question-editor">
    <div class="mb-3">
        <label class="form-label">Текст вопроса <span class="text-danger">*</span></label>
        <textarea class="form-control question-text" rows="3" placeholder="Введите текст вопроса...">{{ $question->question_text ?? '' }}</textarea>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Тип ответа</label>
            <select class="form-select question-type">
                <option value="single">Единичный выбор</option>
                <option value="multiple" selected>Множественный выбор</option>
                <option value="free">Свободный ответ</option>
                <option value="scale">Шкала</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Баллы</label>
            <input type="number" class="form-control question-points" value="{{ $question->points ?? 2 }}" min="1">
        </div>
        <div class="col-md-3 mb-3 d-flex align-items-center">
            <div class="form-check mt-4">
                <input type="checkbox" class="form-check-input question-required" {{ ($question->is_required ?? false) ? 'checked' : '' }}>
                <label class="form-check-label"><i class="bi bi-star-fill text-danger"></i> Обязательный</label>
            </div>
        </div>
        <div class="col-md-2 mb-3 d-flex align-items-center">
            <div class="form-check mt-4">
                <input type="checkbox" class="form-check-input question-other" {{ ($question->has_other ?? false) ? 'checked' : '' }}>
                <label class="form-check-label"><i class="bi bi-plus"></i> Другой</label>
            </div>
        </div>
    </div>
    <div class="limitations-section mb-3">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Минимум выборов</label>
                <input type="number" class="form-control question-min-count" value="{{ $question->min_count ?? 0 }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label">Максимум выборов</label>
                <input type="number" class="form-control question-max-count" value="{{ $question->max_count ?? 0 }}" min="0">
            </div>
        </div>
    </div>
    <div class="answers-section">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label mb-0">Варианты ответов</label>
            <button type="button" class="btn btn-sm btn-primary btn-add-answer"><i class="bi bi-plus-lg"></i> Добавить</button>
        </div>
        <div class="answers-container border rounded p-2">
            @forelse($answers as $answer)
                <div class="answer-row d-flex align-items-center gap-2 mb-2 p-2 border rounded" data-id="{{ $answer->id ?? 'new_' . uniqid() }}">
                    <span class="drag-handle-answer text-muted"><i class="bi bi-grip-vertical"></i></span>
                    <span class="badge bg-secondary">{{ $loop->iteration }}</span>
                    <input type="text" class="form-control answer-text form-control-sm" value="{{ $answer->answer_text ?? '' }}" placeholder="Введите вариант ответа...">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input correct-checkbox" {{ ($answer->is_correct ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label"><span class="text-success"><i class="bi bi-check-lg"></i></span></label>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-answer"><i class="bi bi-trash"></i></button>
                </div>
            @empty
                <div class="text-center text-muted py-3"><i class="bi bi-info-circle"></i> Нет вариантов ответов</div>
            @endforelse
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button type="button" class="btn btn-success btn-save-question"><i class="bi bi-check-lg"></i> Сохранить вопрос</button>
        <button type="button" class="btn btn-secondary btn-clear-form"><i class="bi bi-x-lg"></i> Очистить</button>
    </div>
</div>
