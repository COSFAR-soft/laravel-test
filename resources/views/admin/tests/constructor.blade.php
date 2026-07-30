@extends('admin.layouts.admin')

@section('title', 'Конструктор тестов')

@push('styles')
    <style>
        .question-item{cursor:move;transition:all 0.2s}
        .question-item:hover{background-color:#f8f9fa;border-color:#0d6efd}
        .question-item.dragging{opacity:0.5}
        .question-item .drag-handle{cursor:grab;color:#6c757d}
        .question-item .drag-handle:active{cursor:grabbing}
        .type-badge{font-size:0.7rem;padding:0.25rem 0.5rem}
        .answer-row{transition:all 0.2s}
        .answer-row:hover{background-color:#f8f9fa}
        .answer-row .correct-indicator{font-size:1.2rem;cursor:pointer}
        .question-card{border-left:4px solid #0d6efd}
        .question-card.dragging{border-left-color:#ffc107}
        .list-group-item.active{background-color:#e7f1ff !important;border-color:#0d6efd !important;color:#0d6efd !important}
        .list-group-item.active .text-truncate{color:#0d6efd !important;font-weight:600}
        .list-group-item.active .badge.bg-secondary{background-color:#0d6efd !important}
        .list-group-item.active .drag-handle{color:#0d6efd !important}
        .list-group-item.active .btn-danger{opacity:0.7}
        .answer-row{transition:all 0.2s;background-color:#fff}
        .answer-row:hover{background-color:#f8f9fa;border-color:#0d6efd !important}
        .answers-container{min-height:50px;max-height:300px;overflow-y:auto}
        .sortable-ghost{opacity:0.5;background-color:#e7f1ff}
        .sortable-drag{opacity:0.8;transform:scale(1.02)}
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Конструктор: {{ $test->title }}</h2>
                    <p class="text-muted">{{ $test->description ?? 'Без описания' }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.tests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Назад</a>
                    <a href="{{ route('tests.show', $test) }}" target="_blank" class="btn btn-info"><i class="bi bi-eye"></i> Просмотр</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ul"></i> Вопросы ({{ $questions->count() }})</span>
                    <button class="btn btn-primary btn-sm" onclick="addQuestion()"><i class="bi bi-plus-lg"></i></button>
                </div>
                <div class="card-body p-0">
                    <div id="questionsList" class="list-group list-group-flush">
                        @forelse($questions as $index => $question)
                            <div class="list-group-item question-item d-flex align-items-center"
                                 data-id="{{ $question->id }}" data-order="{{ $question->order }}"
                                 onclick="loadQuestion({{ $question->id }})">
                                <span class="drag-handle me-2"><i class="bi bi-grip-vertical"></i></span>
                                <span class="badge bg-secondary me-2">{{ $index + 1 }}</span>
                                <span class="flex-grow-1 text-truncate">{{ Str::limit($question->question_text, 50) }}</span>
                                <span class="badge bg-info type-badge ms-2">
                                @switch($question->type)
                                        @case('single') Единичный @break
                                        @case('multiple') Множественный @break
                                        @case('free') Свободный @break
                                        @case('scale') Шкала @break
                                    @endswitch
                            </span>
                                <button class="btn btn-danger btn-sm ms-2" onclick="deleteQuestion(event, {{ $question->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-4">
                                <i class="bi bi-info-circle d-block mb-2" style="font-size:2rem;"></i>
                                Нет вопросов. Нажмите "+" чтобы добавить.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><span><i class="bi bi-pencil"></i> Редактор вопроса</span></div>
                <div class="card-body">
                    <div id="questionEditorContainer">
                        @include('admin.questions.single-choice', ['question' => null, 'answers' => []])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentQuestionId = null, isNewQuestion = true;

        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('questionsList');
            if (el) Sortable.create(el, { handle: '.drag-handle', animation: 150, onEnd: function() { reorderQuestions(); } });
            @if($questions->isNotEmpty()) loadQuestion({{ $questions->first()->id }}); @else clearForm(); @endif
            initQuestionHandlers();
        });

        function loadQuestion(id) {
            $.ajax({
                url: '{{ route("admin.questions.index", $test) }}',
                type: 'GET',
                success: function(questions) {
                    const question = questions.find(q => q.id === id);
                    if (question) {
                        currentQuestionId = question.id;
                        isNewQuestion = false;
                        loadQuestionComponent(question);
                        $('.question-item').removeClass('active bg-light');
                        $('.question-item[data-id="' + id + '"]').addClass('active bg-light');
                    }
                },
                error: function() { showError('Ошибка загрузки вопроса'); }
            });
        }

        function loadQuestionComponent(question) {
            const container = $('#questionEditorContainer');
            $.ajax({
                url: '{{ route("admin.questions.partial") }}',
                type: 'POST',
                data: { type: question.type, question_id: question.id, _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        container.html(response.html);
                        fillQuestionData(question);
                        initQuestionHandlers();
                        container.find('.question-type').val(question.type);
                    }
                },
                error: function() { showError('Ошибка загрузки редактора'); }
            });
        }

        function fillQuestionData(question) {
            const container = $('#questionEditorContainer');
            container.find('.question-text').val(question.question_text);
            container.find('.question-points').val(question.points);
            container.find('.question-required').prop('checked', question.is_required == 1);
            container.find('.question-other').prop('checked', question.has_other == 1);
            if (question.type === 'multiple') {
                container.find('.question-min-count').val(question.min_count || 0);
                container.find('.question-max-count').val(question.max_count || 0);
            }
            if (question.type === 'scale') {
                container.find('.question-diapason-start').val(question.diapason_start || 0);
                container.find('.question-diapason-end').val(question.diapason_end || 10);
            }
            const answersContainer = container.find('.answers-container');
            answersContainer.empty();
            if (question.answers && question.answers.length > 0) {
                question.answers.forEach(function(answer) {
                    addAnswerRowToContainer(answersContainer, answer.id, answer.answer_text, answer.is_correct);
                });
            } else {
                addAnswerRowToContainer(answersContainer);
            }
        }

        function addAnswerRowToContainer(container, id = null, text = '', isCorrect = false) {
            const index = container.children().length + 1;
            const rowId = id || 'new_' + Date.now();
            const isMultiple = container.closest('.question-editor').find('.question-type').val() === 'multiple';
            const inputType = isMultiple ? 'checkbox' : 'radio';
            const inputClass = isMultiple ? 'correct-checkbox' : 'correct-radio';
            const nameAttr = isMultiple ? '' : 'name="correct_answer"';
            const html = `<div class="answer-row d-flex align-items-center gap-2 mb-2 p-2 border rounded" data-id="${rowId}">
        <span class="drag-handle-answer text-muted"><i class="bi bi-grip-vertical"></i></span>
        <span class="badge bg-secondary">${index}</span>
        <input type="text" class="form-control answer-text form-control-sm" value="${text}" placeholder="Введите вариант ответа...">
        <div class="form-check">
            <input type="${inputType}" class="form-check-input ${inputClass}" ${nameAttr} ${isCorrect ? 'checked' : ''}>
            <label class="form-check-label"><span class="text-success"><i class="bi bi-check-lg"></i></span></label>
        </div>
        <button type="button" class="btn btn-danger btn-sm btn-remove-answer"><i class="bi bi-trash"></i></button>
    </div>`;
            container.append(html);
        }

        function initQuestionHandlers() {
            const container = $('#questionEditorContainer');
            container.find('.question-type').off('change').on('change', function() { changeQuestionType($(this).val()); });
            container.find('.btn-add-answer').off('click').on('click', function() {
                const answersContainer = $(this).closest('.answers-section').find('.answers-container');
                addAnswerRowToContainer(answersContainer);
            });
            container.find('.btn-remove-answer').off('click').on('click', function() {
                const row = $(this).closest('.answer-row');
                const container = row.closest('.answers-container');
                row.remove();
                container.find('.answer-row').each(function(index) { $(this).find('.badge.bg-secondary').text(index + 1); });
            });
            container.find('.btn-save-question').off('click').on('click', function() { saveQuestion(); });
            container.find('.btn-clear-form').off('click').on('click', function() { clearForm(); });
        }

        function changeQuestionType(type) {
            if (!type) type = $('#questionEditorContainer').find('.question-type').val();
            $.ajax({
                url: '{{ route("admin.questions.partial") }}',
                type: 'POST',
                data: { type: type, question_id: currentQuestionId, _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        const container = $('#questionEditorContainer');
                        const currentText = container.find('.question-text').val();
                        const currentPoints = container.find('.question-points').val();
                        container.html(response.html);
                        if (currentText) container.find('.question-text').val(currentText);
                        if (currentPoints) container.find('.question-points').val(currentPoints);
                        if (currentQuestionId) loadQuestionData(currentQuestionId);
                        initQuestionHandlers();
                    }
                },
                error: function() { showError('Ошибка смены типа вопроса'); }
            });
        }

        function loadQuestionData(id) {
            $.ajax({
                url: '{{ route("admin.questions.index", $test) }}',
                type: 'GET',
                success: function(questions) {
                    const question = questions.find(q => q.id === id);
                    if (question) fillQuestionData(question);
                }
            });
        }

        function saveQuestion() {
            const container = $('#questionEditorContainer');
            const formData = {
                question_text: container.find('.question-text').val(),
                type: container.find('.question-type').val(),
                points: container.find('.question-points').val(),
                is_required: container.find('.question-required').is(':checked') ? 1 : 0,
                has_other: container.find('.question-other').is(':checked') ? 1 : 0,
                min_count: container.find('.question-min-count').val() || 0,
                max_count: container.find('.question-max-count').val() || 0,
                diapason_start: container.find('.question-diapason-start').val() || 0,
                diapason_end: container.find('.question-diapason-end').val() || 10,
                answers: []
            };
            container.find('.answer-row').each(function() {
                const id = $(this).data('id');
                const text = $(this).find('.answer-text').val();
                const isCorrect = $(this).find('.correct-radio:checked').length > 0 || $(this).find('.correct-checkbox:checked').length > 0;
                if (text && text.trim() !== '') formData.answers.push({ id: id, text: text.trim(), is_correct: isCorrect });
            });
            if (!formData.question_text.trim()) { showError('Введите текст вопроса'); return; }
            if (['single', 'multiple'].includes(formData.type) && formData.answers.length === 0) {
                showError('Добавьте хотя бы один вариант ответа'); return;
            }
            const url = currentQuestionId ? '{{ route("admin.questions.update", "") }}/' + currentQuestionId : '{{ route("admin.questions.store", $test) }}';
            const method = currentQuestionId ? 'PUT' : 'POST';
            $.ajax({
                url: url, type: method, data: JSON.stringify(formData), contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) { showSuccess(response.message); location.reload(); }
                    else { showError(response.message || 'Ошибка сохранения'); }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let message = '';
                        Object.values(errors).forEach(function(error) { message += error.join('\n') + '\n'; });
                        showError(message);
                    } else { showError('Ошибка сохранения вопроса'); }
                }
            });
        }

        function addQuestion() {
            clearForm();
            isNewQuestion = true;
            currentQuestionId = null;
            $.ajax({
                url: '{{ route("admin.questions.partial") }}',
                type: 'POST',
                data: { type: 'single', question_id: null, _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        $('#questionEditorContainer').html(response.html);
                        initQuestionHandlers();
                        setTimeout(function() { $('#questionEditorContainer .question-text').focus(); }, 100);
                        showSuccess('Создание нового вопроса. Заполните поля и сохраните.');
                    }
                }
            });
        }

        function deleteQuestion(event, id) {
            event.stopPropagation();
            if (!confirm('Удалить этот вопрос?')) return;
            $.ajax({
                url: '{{ route("admin.questions.destroy", "") }}/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) { if (response.success) { showSuccess(response.message); location.reload(); } },
                error: function() { showError('Ошибка удаления вопроса'); }
            });
        }

        function reorderQuestions() {
            const items = [];
            $('#questionsList .question-item').each(function(index) {
                items.push({ id: $(this).data('id'), order: index });
            });
            $.ajax({
                url: '{{ route("admin.questions.reorder") }}',
                type: 'POST', data: JSON.stringify({ questions: items }), contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        $('#questionsList .question-item').each(function(index) {
                            $(this).find('.badge.bg-secondary').text(index + 1);
                        });
                    }
                }
            });
        }

        function clearForm() {
            currentQuestionId = null;
            isNewQuestion = true;
            $.ajax({
                url: '{{ route("admin.questions.partial") }}',
                type: 'POST',
                data: { type: 'single', question_id: null, _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        $('#questionEditorContainer').html(response.html);
                        initQuestionHandlers();
                        $('.question-item').removeClass('active bg-light');
                        // showSuccess('Форма очищена');
                    }
                }
            });
        }

        function showSuccess(message) {
            const alert = $(`<div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index:9999;min-width:300px;"><i class="bi bi-check-circle-fill me-2"></i>${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
            $('body').append(alert);
            setTimeout(function() { alert.alert('close'); }, 5000);
        }

        function showError(message) {
            const alert = $(`<div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index:9999;min-width:300px;"><i class="bi bi-exclamation-triangle-fill me-2"></i>${message.replace(/\n/g, '<br>')}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`);
            $('body').append(alert);
            setTimeout(function() { alert.alert('close'); }, 8000);
        }
    </script>
@endpush
