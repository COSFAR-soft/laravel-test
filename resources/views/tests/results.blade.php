<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <span>{{ __('Результаты') }}: {{ $test->title }}</span>
            <a href="{{ route('tests.show', $test) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        </div>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    @if(!$result->is_passed)
                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle"></i>
                            {{ __('Проходной балл') }} {{ $test->passing_score }}%.
                            {{ __('Попробуйте еще раз') }}
                        </div>
                    @else
                        <div class="alert alert-success mt-4">
                            <i class="bi bi-star-fill"></i>
                            {{ __('Отличный результат!') }}
                        </div>
                    @endif
                    {{--TODO что-то вставить --}}

                    <div class="display-1 mb-4">
                        @if($result->is_passed)
                            😁 {{--прошел--}}
                        @else
                            😋 {{--не прошел--}}
                        @endif
                    </div>

                    {{-- Процент --}}
                    <div class="display-3 fw-bold mb-2">
                        {{ $result->percentage }}%
                    </div>

                    {{-- Статус --}}
                    <div class="mb-4">
                        <span class="badge {{ $result->is_passed ? 'bg-success' : 'bg-danger' }} fs-5 p-2">
                            {{ $result->is_passed ? 'Тест пройден!' : 'Тест не пройден' }}
                        </span>
                    </div>

                    {{-- Результаты --}}
                    <div class="row g-3 mt-4">
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Правильных ответов') }}</div>
                                <div class="h4 mb-0 text-success">{{ $result->correct_answers }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Всего вопросов') }}</div>
                                <div class="h4 mb-0">{{ $result->total_questions }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Набранные баллы') }}</div>
                                <div class="h4 mb-0">{{ $result->score }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded">
                                <div class="small text-muted">{{ __('Затраченное время') }}</div>
                                <div class="h4 mb-0">{{ $result->time_spent ?? 'N/A' }} мин</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('tests.show', $test) }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> {{ __('К тесту') }}
                        </a>
                        <a href="{{ route('tests.start', $test) }}" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat"></i> {{ __('Пройти заново') }}
                        </a>
                        <a href="{{ route('tests.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> {{ __('Все тесты') }}
                        </a>
                        <a href="{{ route('tests.history') }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history"></i> {{ __('История') }}
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
