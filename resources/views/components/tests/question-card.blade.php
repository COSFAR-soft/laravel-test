@props([
    'question',
    'index'
])

<div class="card mb-4 question-card" id="question-{{ $index + 1 }}">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold">Вопрос {{ $index + 1 }}</span>
            <span class="badge bg-secondary">
                {{ $question->type === 'single' ? 'Один ответ' : 'Несколько ответов' }}
            </span>
        </div>
    </div>
    <div class="card-body">
        <p class="card-text fs-6">{{ $question->question_text }}</p>

        @if($question->type === 'single')
            <div class="mt-3">
                @foreach($question->answers as $answer)
                    <div class="form-check">
                        <input class="form-check-input" type="radio"
                               name="answers[{{ $question->id }}]"
                               id="answer_{{ $answer->id }}"
                               value="{{ $answer->id }}"
                               data-question="{{ $index + 1 }}"
                               required>
                        <label class="form-check-label" for="answer_{{ $answer->id }}">
                            {{ $answer->answer_text }}
                        </label>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mt-3">
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle"></i>
                    {{ __('Выберите все правильные варианты') }}
                </div>
                @foreach($question->answers as $answer)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="answers[{{ $question->id }}][]"
                               id="answer_{{ $answer->id }}"
                               value="{{ $answer->id }}"
                               data-question="{{ $index + 1 }}">
                        <label class="form-check-label" for="answer_{{ $answer->id }}">
                            {{ $answer->answer_text }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .question-card {
        scroll-margin-top: 80px;
        transition: all 0.3s ease;
    }

    .question-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }

    .form-check {
        display: flex !important;
        align-items: center !important;
        padding: 0.5rem 0.75rem !important;
        margin-bottom: 0.25rem !important;
        border-radius: 8px !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        background-color: transparent !important;
        border: 1px solid transparent !important;
    }

    .form-check:hover {
        background-color: #f0f4ff !important;
        border-color: #d1d9e6 !important;
        transform: translateX(4px) !important;
    }

    .form-check:has(input:checked) {
        background-color: #e8f5e9 !important;
        border-color: #4caf50 !important;
    }

    .form-check-input {
        flex-shrink: 0 !important;
        width: 18px !important;
        height: 18px !important;
        margin: 0 !important;
        margin-right: 12px !important;
        cursor: pointer !important;
        border: 2px solid #c4c9d4 !important;
        transition: all 0.2s ease !important;
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
    }

    .form-check-input[type="radio"] {
        border-radius: 50% !important;
    }

    .form-check-input[type="checkbox"] {
        border-radius: 4px !important;
    }

    .form-check-input:hover {
        border-color: #6c7a8e !important;
        transform: scale(1.05) !important;
    }

    .form-check-input:checked {
        background-color: #4caf50 !important;
        border-color: #4caf50 !important;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2) !important;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.15) !important;
        border-color: #4caf50 !important;
    }

    .form-check-input:active {
        transform: scale(0.95) !important;
    }

    .form-check-label {
        flex: 1 !important;
        cursor: pointer !important;
        padding: 0 !important;
        margin: 0 !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
        color: #1a202c !important;
        user-select: none !important;
        padding-left: 0 !important;
    }

    .form-check:has(input:checked) .form-check-label {
        font-weight: 600 !important;
        color: #1e4620 !important;
    }
</style>
