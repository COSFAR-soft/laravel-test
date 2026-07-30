<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>{{ $test->title }}</span>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <x-tests.timer :timeLeft="$timeLeft" />
                <span class="badge bg-secondary fs-6 p-2">
                    <i class="bi bi-list-ol"></i>
                    {{ count($questions) }} вопросов
                </span>
                <span class="badge bg-success fs-6 p-2">
                    <i class="bi bi-check-circle"></i>
                    <span id="answeredCount">0</span> из {{ count($questions) }} отвечено
                </span>
            </div>
        </div>
    </x-slot>

    @if(session('warning'))
        <x-ui.alert type="warning" :message="session('warning')" />
    @endif

    <form action="{{ route('tests.submit', $test) }}" method="POST" id="testForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                @foreach($questions as $index => $question)
                    <x-tests.question-card :question="$question" :index="$index" />
                @endforeach

                <div class="d-grid mb-4">
                    <x-ui.button type="submit" color="success" size="lg" id="submitBtn">
                        <i class="bi bi-check-circle"></i> {{ __('Завершить тест') }}
                    </x-ui.button>
                </div>
            </div>

            <div class="col-lg-4">
                <x-tests.progress-panel
                    :questions="$questions"
                    :totalQuestions="count($questions)"
                />
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const questionIds = [
                    @foreach($questions as $question)
                        {{ $question->id }},
                    @endforeach
                ];
                const totalQuestions = questionIds.length;
                console.log('ID вопросов:', questionIds);

                /**
                 * Обновление прогресса
                 */
                function updateProgress() {
                    let answered = 0;

                    questionIds.forEach(function (questionId) {
                        const radio = document.querySelector('input[type="radio"][name="answers[' + questionId + ']"]:checked');
                        if (radio) {
                            answered++;
                            return;
                        }

                        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="answers[' + questionId + '][]"]:checked');
                        if (checkboxes.length > 0) {
                            answered++;
                        }
                    });

                    const percentage = Math.round((answered / totalQuestions) * 100);
                    const remaining = totalQuestions - answered;

                    document.getElementById('answeredCount').textContent = answered;
                    document.getElementById('answeredCountFooter').textContent = answered;
                    document.getElementById('remainingCount').textContent = remaining;

                    const progressBar = document.getElementById('progressBar');
                    progressBar.style.width = percentage + '%';
                    progressBar.textContent = percentage + '%';

                    progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated';


                    document.querySelectorAll('.status-btn').forEach(function (btn) {
                        btn.className = 'btn btn-outline-secondary btn-sm m-1 status-btn';
                    });

                    questionIds.forEach(function (questionId, index) {
                        const radio = document.querySelector('input[type="radio"][name="answers[' + questionId + ']"]:checked');
                        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="answers[' + questionId + '][]"]:checked');

                        if (radio || checkboxes.length > 0) {
                            const btn = document.querySelector('.status-btn[data-question="' + (index + 1) + '"]');
                            if (btn) {
                                btn.className = 'btn btn-success btn-sm m-1 status-btn';
                            }
                        }
                    });

                    console.log(answered + '/' + totalQuestions + ' ' + percentage + '%');
                }

                /**
                 * обработчик radio
                 */
                document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(function (input) {
                    input.addEventListener('change', function () {
                        updateProgress();
                    });
                });

                /**
                 * плавная прокрутка
                 */
                document.querySelectorAll('.status-btn').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const questionNum = this.getAttribute('data-question');

                        const target = document.getElementById('question-' + questionNum);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });

                            // Подсветка карточки
                            target.style.transition = 'box-shadow 0.3s ease';
                            target.style.boxShadow = '0 0 0 3px #4caf50, 0 0 20px rgba(76, 175, 80, 0.3)';

                            setTimeout(function () {
                                target.style.boxShadow = '';
                            }, 2000);
                        } else {

                        }
                    });
                });

                setTimeout(updateProgress, 100);
                setTimeout(updateProgress, 500);

            });
        </script>

        <style>

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

            .animate-pulse {
                animation: pulse 1s ease-in-out infinite;
            }

            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
            }

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

            .border {
                border-color: #dee2e6 !important;
            }

            .border .fw-bold {
                font-size: 1.2rem;
            }
        </style>
    @endpush
</x-app-layout>
