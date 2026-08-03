@extends('admin.layouts.admin')

@section('title', 'Конструктор тестов')

@push('styles')
    <style>
        .question-item {
            cursor: move;
            transition: all 0.2s
        }

        .question-item:hover {
            background-color: #f8f9fa;
            border-color: #0d6efd
        }

        .question-item.dragging {
            opacity: 0.5
        }

        .question-item .drag-handle {
            cursor: grab;
            color: #6c757d
        }

        .question-item .drag-handle:active {
            cursor: grabbing
        }

        .type-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem
        }

        .answer-row {
            transition: all 0.2s
        }

        .answer-row:hover {
            background-color: #f8f9fa
        }

        .answer-row .correct-indicator {
            font-size: 1.2rem;
            cursor: pointer
        }

        .question-card {
            border-left: 4px solid #0d6efd
        }

        .question-card.dragging {
            border-left-color: #ffc107
        }

        .list-group-item.active {
            background-color: #e7f1ff !important;
            border-color: #0d6efd !important;
            color: #0d6efd !important
        }

        .list-group-item.active .text-truncate {
            color: #0d6efd !important;
            font-weight: 600
        }

        .list-group-item.active .badge.bg-secondary {
            background-color: #0d6efd !important
        }

        .list-group-item.active .drag-handle {
            color: #0d6efd !important
        }

        .list-group-item.active .btn-danger {
            opacity: 0.7
        }

        .answer-row {
            transition: all 0.2s;
            background-color: #fff
        }

        .answer-row:hover {
            background-color: #f8f9fa;
            border-color: #0d6efd !important
        }

        .answers-container {
            min-height: 50px;
            max-height: 300px;
            overflow-y: auto
        }

        .sortable-ghost {
            opacity: 0.5;
            background-color: #e7f1ff
        }

        .sortable-drag {
            opacity: 0.8;
            transform: scale(1.02)
        }
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
                    <a href="{{ route('admin.tests.index') }}" class="btn btn-outline-secondary"><i
                            class="bi bi-arrow-left"></i> Назад</a>
                    <a href="{{ route('tests.show', $test) }}" target="_blank" class="btn btn-info"><i
                            class="bi bi-eye"></i> Просмотр</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ul"></i> Вопросы ({{ $questions->count() }})</span>
                    <button class="btn btn-primary btn-sm" onclick="addQuestion()"><i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="questionsList" class="list-group list-group-flush">
                        @forelse($questions as $index => $question)
                            <div class="list-group-item question-item d-flex align-items-center"
                                 data-id="{{ $question->id }}" data-order="{{ $question->order }}"
                                 onclick="loadQuestion({{ $question->id }})">
                                <span class="drag-handle me-2"><i class="bi bi-grip-vertical"></i></span>
                                <span class="badge bg-secondary me-2">{{ $index + 1 }}</span>
                                <span
                                    class="flex-grow-1 text-truncate">{{ Str::limit($question->question_text, 50) }}</span>
                                <span class="badge bg-info type-badge ms-2">
                                @switch($question->type)
                                        @case('single') Единичный @break
                                        @case('multiple') Множественный @break
                                        @case('free') Свободный @break
                                        @case('scale') Шкала @break
                                    @endswitch
                            </span>
                                <button class="btn btn-danger btn-sm ms-2"
                                        onclick="deleteQuestion(event, {{ $question->id }})">
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
        let currentQuestionId = null;
        let isNewQuestion = true;
        let isSaving = false;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        //ИНИЦИАЛИЗАЦИЯ
        document.addEventListener('DOMContentLoaded', function () {
            initSortable();
            @if($questions->isNotEmpty())
            loadQuestion({{ $questions->first()->id }});
            @else
            clearForm();
            @endif
            initQuestionHandlers();
        });

        //FETCH HELPER
        async function apiRequest(url, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            };

            if (data && (method === 'POST' || method === 'PUT' || method === 'DELETE')) {
                options.body = JSON.stringify(data);
            }

            try {
                const response = await fetch(url, options);

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw {
                        status: response.status,
                        data: errorData
                    };
                }

                return await response.json();
            } catch (error) {
                if (error.status) throw error;
                throw {
                    status: 500,
                    data: {message: 'Ошибка сети'}
                };
            }
        }

        //инициализация Sortablejs
        function initSortable() {
            const el = document.getElementById('questionsList');
            if (el && typeof Sortable !== 'undefined') {
                Sortable.create(el, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: function () {
                        reorderQuestions();
                    }
                });
            }
        }

        //загрузка вопроса
        async function loadQuestion(id) {
            try {
                const questions = await apiRequest('{{ route("admin.questions.index", $test) }}');
                const question = questions.find(q => q.id === id);

                if (question) {
                    currentQuestionId = question.id;
                    isNewQuestion = false;
                    await loadQuestionComponent(question);

                    document.querySelectorAll('.question-item').forEach(el => {
                        el.classList.remove('active', 'bg-light');
                    });
                    const activeItem = document.querySelector(`.question-item[data-id="${id}"]`);
                    if (activeItem) {
                        activeItem.classList.add('active', 'bg-light');
                    }
                }
            } catch (error) {
                window.showNotification('Ошибка загрузки вопроса', 'error');
            }
        }

        //загрузка компонентов
        async function loadQuestionComponent(question) {
            try {
                const response = await apiRequest('{{ route("admin.questions.partial") }}', 'POST', {
                    type: question.type,
                    question_id: question.id
                });

                if (response.success) {
                    const container = document.getElementById('questionEditorContainer');
                    container.innerHTML = response.html;
                    fillQuestionData(question);
                    initQuestionHandlers();

                    const typeSelect = container.querySelector('.question-type');
                    if (typeSelect) typeSelect.value = question.type;
                }
            } catch (error) {
                window.showNotification('Ошибка загрузки редактора', 'error');
            }
        }

        //заполнение вопроса
        function fillQuestionData(question) {
            const container = document.getElementById('questionEditorContainer');
            if (!container) return;

            const textarea = container.querySelector('.question-text');
            if (textarea) textarea.value = question.question_text || '';

            const points = container.querySelector('.question-points');
            if (points) points.value = question.points || 1;

            const required = container.querySelector('.question-required');
            if (required) required.checked = question.is_required == 1;

            const other = container.querySelector('.question-other');
            if (other) other.checked = question.has_other == 1;

            //варианты ответов
            const answersContainer = container.querySelector('.answers-container');
            if (answersContainer) {
                answersContainer.innerHTML = '';
                if (question.answers && question.answers.length > 0) {
                    question.answers.forEach(answer => {
                        addAnswerRow(answersContainer, answer.id, answer.answer_text, answer.is_correct);
                    });
                } else {
                    addAnswerRow(answersContainer);
                }
            }
        }

        //добавление ответа
        function addAnswerRow(container, id = null, text = '', isCorrect = false) {
            const index = container.querySelectorAll('.answer-row').length + 1;
            const rowId = id || 'new_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);

            const typeSelect = container.closest('.question-editor')?.querySelector('.question-type');
            const isMultiple = typeSelect ? typeSelect.value === 'multiple' : false;
            const inputType = isMultiple ? 'checkbox' : 'radio';
            const inputClass = isMultiple ? 'correct-checkbox' : 'correct-radio';
            const nameAttr = isMultiple ? '' : 'name="correct_answer"';
            const checkedAttr = isCorrect ? 'checked' : '';

            const div = document.createElement('div');
            div.className = 'answer-row d-flex align-items-center gap-2 mb-2 p-2 border rounded';
            div.dataset.id = rowId;
            div.innerHTML = `
            <span class="drag-handle-answer text-muted"><i class="bi bi-grip-vertical"></i></span>
            <span class="badge bg-secondary">${index}</span>
            <input type="text" class="form-control answer-text form-control-sm" value="${escapeHtml(text)}" placeholder="Введите вариант ответа...">
            <div class="form-check">
                <input type="${inputType}" class="form-check-input ${inputClass}" ${nameAttr} ${checkedAttr}>
                <label class="form-check-label"><span class="text-success"><i class="bi bi-check-lg"></i></span></label>
            </div>
            <button type="button" class="btn btn-danger btn-sm btn-remove-answer"><i class="bi bi-trash"></i></button>
            `;

            container.appendChild(div);

            //удаление
            div.querySelector('.btn-remove-answer').addEventListener('click', function (e) {
                e.stopPropagation();
                div.remove();
            });
        }

        function initQuestionHandlers() {
            const container = document.getElementById('questionEditorContainer');
            if (!container) return;

            // Смена типа вопроса
            const typeSelect = container.querySelector('.question-type');
            if (typeSelect) {
                typeSelect.onchange = function () {
                    changeQuestionType(this.value);
                };
            }

            // Добавление ответа
            const addBtn = container.querySelector('.btn-add-answer');
            if (addBtn) {
                addBtn.onclick = function () {
                    const answersContainer = this.closest('.answers-section')?.querySelector('.answers-container');
                    if (answersContainer) {
                        addAnswerRow(answersContainer);
                    }
                };
            }

            // Сохранение
            const saveBtn = container.querySelector('.btn-save-question');
            if (saveBtn) {
                saveBtn.onclick = function () {
                    saveQuestion();
                };
            }

            // Очистка
            const clearBtn = container.querySelector('.btn-clear-form');
            if (clearBtn) {
                clearBtn.onclick = function () {
                    clearForm();
                };
            }
        }

        // смена типа вопроса
        async function changeQuestionType(type) {
            if (!type) {
                const select = document.querySelector('#questionEditorContainer .question-type');
                if (select) type = select.value;
            }

            const container = document.getElementById('questionEditorContainer');
            const currentText = container?.querySelector('.question-text')?.value || '';
            const currentPoints = container?.querySelector('.question-points')?.value || '';

            try {
                const response = await apiRequest('{{ route("admin.questions.partial") }}', 'POST', {
                    type: type,
                    question_id: currentQuestionId
                });

                if (response.success) {
                    container.innerHTML = response.html;
                    if (currentText) {
                        const textarea = container.querySelector('.question-text');
                        if (textarea) textarea.value = currentText;
                    }
                    if (currentPoints) {
                        const points = container.querySelector('.question-points');
                        if (points) points.value = currentPoints;
                    }
                    if (currentQuestionId) {
                        await loadQuestionData(currentQuestionId);
                    }
                    initQuestionHandlers();
                }
            } catch (error) {
                window.showNotification('Ошибка смены типа вопроса', 'error');
            }
        }


        //загрузка
        async function loadQuestionData(id) {
            try {
                const questions = await apiRequest('{{ route("admin.questions.index", $test) }}');
                const question = questions.find(q => q.id === id);
                if (question) {
                    fillQuestionData(question);
                }
            } catch (error) {
                window.showNotification('Ошибка загрузки данных вопроса', 'error');
            }
        }

        //сохранение
        async function saveQuestion() {
            if (isSaving) return;

            const container = document.getElementById('questionEditorContainer');
            if (!container) return;

            const formData = {
                question_text: container.querySelector('.question-text')?.value?.trim() || '',
                type: container.querySelector('.question-type')?.value || 'single',
                points: parseInt(container.querySelector('.question-points')?.value) || 1,
                is_required: container.querySelector('.question-required')?.checked ? 1 : 0,
                has_other: container.querySelector('.question-other')?.checked ? 1 : 0,
                min_count: parseInt(container.querySelector('.question-min-count')?.value) || 0,
                max_count: parseInt(container.querySelector('.question-max-count')?.value) || 0,
                diapason_start: parseInt(container.querySelector('.question-diapason-start')?.value) || 0,
                diapason_end: parseInt(container.querySelector('.question-diapason-end')?.value) || 10,
                answers: []
            };

            if (!formData.question_text) {
                window.showNotification('Введите текст вопроса', 'error');
                container.querySelector('.question-text')?.focus();
                return;
            }

            // Сбор ответов
            const answersContainer = container.querySelector('.answers-container');
            if (answersContainer) {
                answersContainer.querySelectorAll('.answer-row').forEach(row => {
                    const id = row.dataset.id;
                    const text = row.querySelector('.answer-text')?.value?.trim() || '';
                    const isCorrect = row.querySelector('.correct-radio:checked') !== null ||
                        row.querySelector('.correct-checkbox:checked') !== null;
                    if (text) {
                        formData.answers.push({id, text, is_correct: isCorrect});
                    }
                });
            }

            if (['single', 'multiple'].includes(formData.type) && formData.answers.length === 0) {
                window.showNotification('Добавьте хотя бы один вариант ответа', 'error');
                return;
            }

            const isEdit = currentQuestionId && !isNewQuestion;
            const url = isEdit
                ? '{{ route("admin.questions.update", "") }}/' + currentQuestionId
                : '{{ route("admin.questions.store", $test) }}';
            const method = isEdit ? 'PUT' : 'POST';

            isSaving = true;

            try {
                const response = await apiRequest(url, method, formData);
                isSaving = false;

                window.showNotification(response.message || (isEdit ? 'Вопрос обновлен!' : 'Вопрос добавлен!'), 'success');
                await reloadQuestionsList();

                if (isNewQuestion) {
                    clearForm();
                } else if (response.question) {
                    fillQuestionData(response.question);
                }
            } catch (error) {
                isSaving = false;
                if (error.status === 422) {
                    const errors = error.data.errors || {};
                    let message = '';
                    Object.values(errors).forEach(err => {
                        message += (Array.isArray(err) ? err.join('\n') : err) + '\n';
                    });
                    window.showNotification(message || 'Ошибка валидации', 'error');
                } else {
                    window.showNotification(error.data?.message || 'Ошибка сохранения вопроса', 'error');
                }
            }
        }

        //обновление списка вопросов
        async function reloadQuestionsList() {
            try {
                const questions = await apiRequest('{{ route("admin.questions.index", $test) }}');
                const list = document.getElementById('questionsList');
                if (!list) return;

                list.innerHTML = '';

                if (questions.length === 0) {
                    list.innerHTML = `
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-info-circle d-block mb-2" style="font-size:2rem;"></i>
                        Нет вопросов. Нажмите "+" чтобы добавить.
                    </div>
                `;
                    return;
                }

                const typeLabels = {
                    'single': 'Единичный',
                    'multiple': 'Множественный'
                };

                questions.forEach((q, index) => {
                    const div = document.createElement('div');
                    div.className = 'list-group-item question-item d-flex align-items-center';
                    div.dataset.id = q.id;
                    div.dataset.order = q.order;
                    div.onclick = () => loadQuestion(q.id);
                    div.innerHTML = `
                    <span class="drag-handle me-2"><i class="bi bi-grip-vertical"></i></span>
                    <span class="badge bg-secondary me-2">${index + 1}</span>
                    <span class="flex-grow-1 text-truncate">${escapeHtml(q.question_text)}</span>
                    <span class="badge bg-info type-badge ms-2">${typeLabels[q.type] || q.type}</span>
                    <button class="btn btn-danger btn-sm ms-2" onclick="event.stopPropagation(); deleteQuestion(${q.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                    list.appendChild(div);
                });

                initSortable();

                if (currentQuestionId) {
                    const activeItem = document.querySelector(`.question-item[data-id="${currentQuestionId}"]`);
                    if (activeItem) {
                        activeItem.classList.add('active', 'bg-light');
                    }
                }
            } catch (error) {
                window.showNotification('Ошибка обновления списка вопросов', 'error');
            }
        }

        //добавление вопроса
        function addQuestion() {
            clearForm();
            isNewQuestion = true;
            currentQuestionId = null;

            apiRequest('{{ route("admin.questions.partial") }}', 'POST', {
                type: 'single',
                question_id: null
            })
                .then(response => {
                    if (response.success) {
                        const container = document.getElementById('questionEditorContainer');
                        container.innerHTML = response.html;
                        initQuestionHandlers();
                        setTimeout(() => {
                            container.querySelector('.question-text')?.focus();
                        }, 100);
                        window.showNotification('Создание нового вопроса. Заполните поля и сохраните.', 'success');
                    }
                })
                .catch(() => {
                    window.showNotification('Ошибка создания нового вопроса', 'error');
                });
        }

        //удаление вопроса
        async function deleteQuestion(id) {
            if (!confirm('Удалить этот вопрос? Все связанные ответы будут также удалены.')) return;

            try {
                const response = await apiRequest('{{ route("admin.questions.destroy", "") }}/' + id, 'DELETE');
                if (response.success) {
                    window.showNotification(response.message || 'Вопрос удален!', 'success');

                    // Удаляем из DOM
                    const item = document.querySelector(`.question-item[data-id="${id}"]`);
                    if (item) item.remove();

                    // Перенумерация
                    document.querySelectorAll('#questionsList .question-item .badge.bg-secondary')
                        .forEach((badge, index) => {
                            badge.textContent = index + 1;
                        });

                    if (currentQuestionId == id) {
                        clearForm();
                    }
                }
            } catch (error) {
                window.showNotification('Ошибка удаления вопроса', 'error');
            }
        }

        //изменение порядка вопросов
        async function reorderQuestions() {
            const items = [];
            document.querySelectorAll('#questionsList .question-item').forEach((el, index) => {
                items.push({
                    id: parseInt(el.dataset.id),
                    order: index
                });
            });

            try {
                const response = await apiRequest('{{ route("admin.questions.reorder") }}', 'POST', {questions: items});
                if (response.success) {
                    document.querySelectorAll('#questionsList .question-item .badge.bg-secondary')
                        .forEach((badge, index) => {
                            badge.textContent = index + 1;
                        });
                }
            } catch (error) {
                window.showNotification('Ошибка обновления порядка', 'error');
            }
        }

        //очистка
        function clearForm() {
            currentQuestionId = null;
            isNewQuestion = true;

            apiRequest('{{ route("admin.questions.partial") }}', 'POST', {
                type: 'single',
                question_id: null
            })
                .then(response => {
                    if (response.success) {
                        const container = document.getElementById('questionEditorContainer');
                        container.innerHTML = response.html;
                        initQuestionHandlers();
                        document.querySelectorAll('.question-item').forEach(el => {
                            el.classList.remove('active', 'bg-light');
                        });
                    }
                })
                .catch(() => {
                    window.showNotification('Ошибка очистки формы', 'error');
                });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        window.loadQuestion = loadQuestion;
        window.deleteQuestion = deleteQuestion;
        window.addQuestion = addQuestion;
        window.reorderQuestions = reorderQuestions;
        window.clearForm = clearForm;
        window.saveQuestion = saveQuestion;
        window.changeQuestionType = changeQuestionType;
        window.reloadQuestionsList = reloadQuestionsList;
    </script>
@endpush
