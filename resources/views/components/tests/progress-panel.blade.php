@props([
    'questions',
    'answeredCount' => 0,
    'totalQuestions' => 0
])

<div class="card sticky-top" style="top: 80px;">
    <div class="card-body">
        <h6 class="card-title text-center mb-3">
            <i class="bi bi-graph-up-arrow"></i> {{ __('Прогресс') }}
        </h6>

        <!-- Прогресс-бар -->
        <div class="progress mb-3" style="height: 30px;">
            <div id="progressBar"
                 class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                 style="width: 0%;">
                0%
            </div>
        </div>

        <!-- Статистика -->
        <div class="row text-center small mb-3">
            <div class="col-4">
                <div class="border rounded p-2">
                    <div class="fw-bold text-success" id="answeredCountFooter">0</div>
                    <div class="text-muted">Отвечено</div>
                </div>
            </div>
            <div class="col-4">
                <div class="border rounded p-2">
                    <div class="fw-bold text-warning" id="remainingCount">{{ $totalQuestions }}</div>
                    <div class="text-muted">Осталось</div>
                </div>
            </div>
            <div class="col-4">
                <div class="border rounded p-2">
                    <div class="fw-bold text-primary">{{ $totalQuestions }}</div>
                    <div class="text-muted">Всего</div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Навигация по вопросам -->
        <div id="questionStatus" class="d-flex flex-wrap justify-content-center gap-1">
            @foreach($questions as $index => $question)
                <button type="button"
                        class="btn btn-outline-secondary btn-sm status-btn"
                        data-question="{{ $index + 1 }}"
                        title="Перейти к вопросу {{ $index + 1 }}">
                    {{ $index + 1 }}
                </button>
            @endforeach
        </div>

        <hr>

        <div class="text-center small text-muted">
            <i class="bi bi-info-circle"></i>
            Нажмите на номер вопроса для быстрой навигации
        </div>
    </div>
</div>

<style>
    .sticky-top {
        top: 80px;
    }

    .status-btn {
        min-width: 36px;
        height: 36px;
        font-size: 0.8rem;
        padding: 0;
        border-radius: 50%;
        font-weight: bold;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .status-btn:hover {
        transform: scale(1.15);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .status-btn:active {
        transform: scale(0.95);
    }

    .status-btn.btn-success {
        color: #fff;
        background-color: #198754;
        border-color: #198754;
    }

    .status-btn.btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 8px;
        overflow: hidden;
    }

    .progress-bar {
        font-weight: bold;
        font-size: 0.9rem;
        transition: width 0.5s ease;
    }

    .border {
        border-color: #dee2e6 !important;
    }

    .border .fw-bold {
        font-size: 1.2rem;
    }
</style>
