@props(['stats'])

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <x-ui.stats-card title="Всего тестов" value="{{ $stats['total'] }}" color="primary" />
    </div>
    <div class="col-md-3 col-6">
        <x-ui.stats-card title="Пройдено" value="{{ $stats['passed'] }}" color="success" />
    </div>
    <div class="col-md-3 col-6">
        <x-ui.stats-card title="Не пройдено" value="{{ $stats['total'] - $stats['passed'] }}" color="danger" />
    </div>
    <div class="col-md-3 col-6">
        <x-ui.stats-card title="Средний балл" value="{{ round($stats['avg_score']) }}%" color="info" />
    </div>
</div>
