@props(['question' => null, 'index' => 0])

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
                <option value="multiple">Множественный выбор</option>
                <option value="free">Свободный ответ</option>
                <option value="scale" selected>Шкала</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Баллы</label>
            <input type="number" class="form-control question-points" value="{{ $question->points ?? 1 }}" min="1">
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
    <div class="scale-section">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Начало шкалы</label>
                <input type="number" class="form-control question-diapason-start" value="{{ $question->diapason_start ?? 0 }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Конец шкалы</label>
                <input type="number" class="form-control question-diapason-end" value="{{ $question->diapason_end ?? 10 }}">
            </div>
        </div>
        <div class="mt-3">
            <label class="form-label">Пример шкалы</label>
            <div class="border rounded p-3 bg-light">
                <div class="d-flex justify-content-between">
                    <span class="badge bg-secondary">0</span>
                    <span class="badge bg-secondary">1</span>
                    <span class="badge bg-secondary">2</span>
                    <span class="badge bg-secondary">3</span>
                    <span class="badge bg-secondary">4</span>
                    <span class="badge bg-secondary">5</span>
                    <span class="badge bg-secondary">6</span>
                    <span class="badge bg-secondary">7</span>
                    <span class="badge bg-secondary">8</span>
                    <span class="badge bg-secondary">9</span>
                    <span class="badge bg-secondary">10</span>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button type="button" class="btn btn-success btn-save-question"><i class="bi bi-check-lg"></i> Сохранить вопрос</button>
        <button type="button" class="btn btn-secondary btn-clear-form"><i class="bi bi-x-lg"></i> Очистить</button>
    </div>
</div>
