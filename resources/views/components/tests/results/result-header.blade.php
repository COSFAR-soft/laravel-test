@props([
    'result',
    'test'
])

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>
            @if(isset($user))
                <i class="bi bi-person-circle"></i> {{ $user->name }}
                <span class="text-muted fs-6">— {{ $test->title }}</span>
            @else
                Результаты: {{ $test->title }}
            @endif
        </h2>
        @if(isset($user))
            <p class="text-muted mb-0">{{ $user->email }}</p>
        @endif
    </div>
    <div>
        @if(isset($user))
            <a href="{{ route('admin.dashboard.user-stats', $user) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Назад к пользователю
            </a>
        @else
            <a href="{{ route('tests.show', $test) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Назад
            </a>
        @endif
    </div>
</div>
