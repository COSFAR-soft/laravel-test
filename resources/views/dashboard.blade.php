<x-app-layout>
    <x-slot name="header">
        {{ __('Статистика') }}
    </x-slot>

    <div class="row">
        <div class="col-md-4 col-6 mb-3">
            <x-ui.stats-card
                title="Всего тестов"
                value="{{ $stats['total_tests'] }}"
                color="primary"
            />
        </div>
        <div class="col-md-4 col-6 mb-3">
            <x-ui.stats-card
                title="Пройдено"
                value="{{ $stats['completed_tests'] }}"
                color="success"
            />
        </div>
        <div class="col-md-4 col-6 mb-3">
            <x-ui.stats-card
                title="Средний балл"
                value="{{ $stats['average_score'] }}%"
                color="info"
            />
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('tests.index') }}" class="btn btn-primary">
            <i class="bi bi-list-check"></i> Перейти к тестам
        </a>
        <a href="{{ route('tests.history') }}" class="btn btn-outline-secondary">
            <i class="bi bi-clock-history"></i> Моя история
        </a>
    </div>
</x-app-layout>
