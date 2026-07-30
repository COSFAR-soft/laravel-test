<x-app-layout>
    <x-slot name="header">
        {{ __('Статистика') }}
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Добро пожаловать!') }}</h5>
                    <p class="card-text">
                        {{ __("Вы успешно авторизованы!") }}
                    </p>

                    <!-- Статистика -->
                    <div class="row mt-4">
                        @php
                            $totalTests = \App\Models\Test::count();
                            $completedTests = \App\Models\TestResult::where('user_id', auth()->id())
                                ->whereNotNull('completed_at')
                                ->count();
                            $averageScore = \App\Models\TestResult::where('user_id', auth()->id())
                                ->whereNotNull('completed_at')
                                ->avg('score') ?? 0;
                            $pendingTests = \App\Models\TestResult::where('user_id', auth()->id())
                                ->whereNull('completed_at')
                                ->count();
                        @endphp

                        <div class="col-md-4 col-6 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="display-6">{{ $totalTests }}</h3>
                                    <p class="mb-0">Всего тестов</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="display-6">{{ $completedTests }}</h3>
                                    <p class="mb-0">Пройдено</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="display-6">{{ round($averageScore) }}%</h3>
                                    <p class="mb-0">Средний балл</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Быстрые действия -->
                    <div class="mt-4">
                        <a href="{{ route('tests.index') }}" class="btn btn-primary">
                            <i class="bi bi-list-check"></i> Перейти к тестам
                        </a>
                        <a href="{{ route('tests.history') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-clock-history"></i> Моя история
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
