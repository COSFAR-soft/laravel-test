<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>{{ $test->title }}</span>
            <a href="{{ route('tests.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('О тесте') }}</h5>
                    <p class="card-text">{{ $test->description ?? 'Описание отсутствует' }}</p>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-list-ul fs-4 me-2 text-primary"></i>
                                <div>
                                    <div class="small text-muted">{{ __('Вопросов') }}</div>
                                    <div class="fw-bold">{{ $test->questions_count }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock fs-4 me-2 text-warning"></i>
                                <div>
                                    <div class="small text-muted">{{ __('Время на прохождение') }}</div>
                                    <div class="fw-bold">{{ $test->time_limit }} минут</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle fs-4 me-2 text-success"></i>
                                <div>
                                    <div class="small text-muted">{{ __('Проходной балл') }}</div>
                                    <div class="fw-bold">{{ $test->passing_score }}%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-star fs-4 me-2 text-danger"></i>
                                <div>
                                    <div class="small text-muted">{{ __('Максимальный балл') }}</div>
                                    <div class="fw-bold">{{ $test->total_points }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($test->questions_count > 0 && $test->questions->first())
                        <div class="mt-4">
                            <h6>{{ __('Пример вопроса:') }}</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $test->questions->first()->question_text }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">

                    @if($inProgress ?? false)
                        <div class="mb-3">
                            <i class="bi bi-hourglass-split display-3 text-warning"></i>
                            <h5 class="mt-2">{{ __('Тест начат!') }}</h5>
                            <p class="text-muted small">{{ __('Продолжите прохождение') }}</p>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('tests.take', $test) }}" class="btn btn-warning">
                                <i class="bi bi-play-fill"></i> {{ __('Продолжить') }}
                            </a>
                        </div>
                    @elseif($result && $result->completed_at)
                        <div class="mb-3">
                            <div class="display-3 mb-2">
                                {{ $result->percentage }}%
                            </div>
                            <div>
                                <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }} fs-6 p-2">
                                    {{ $result->is_passed ? 'Пройден' : 'Не пройден' }}
                                </span>
                            </div>
                            <p class="text-muted small mt-2">
                                {{ $result->correct_answers }} / {{ $result->total_questions }}
                            </p>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('tests.results', $test) }}" class="btn btn-outline-primary">
                                <i class="bi bi-graph-up"></i> {{ __('Результаты') }}
                            </a>
                            <a href="{{ route('tests.start', $test) }}" class="btn btn-primary">
                                <i class="bi bi-arrow-repeat"></i> {{ __('Пройти заново') }}
                            </a>
                        </div>
                    @else
                        <div class="mb-3">
                            <i class="bi bi-file-text display-3 text-primary"></i>
                            <h5 class="mt-2">{{ __('Готовы начать?') }}</h5>
                            <p class="text-muted small">
                                {{ __('У вас будет') }} {{ $test->time_limit }} {{ __('минут') }}
                            </p>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('tests.start', $test) }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-play-fill"></i> {{ __('Начать тест') }}
                            </a>
                        </div>
                    @endif

                    <hr>

                    <div class="text-muted small">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Количество попыток: неограничено') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
