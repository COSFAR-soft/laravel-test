@props([
    'test'
])

<div class="d-flex justify-content-center gap-3 flex-wrap">
    <a href="{{ route('tests.show', $test) }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> К тесту
    </a>
    <a href="{{ route('tests.start', $test) }}" class="btn btn-primary">
        <i class="bi bi-arrow-repeat"></i> Пройти заново
    </a>
    <a href="{{ route('tests.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-list"></i> Все тесты
    </a>
    <a href="{{ route('tests.history') }}" class="btn btn-outline-info">
        <i class="bi bi-clock-history"></i> История
    </a>
</div>
