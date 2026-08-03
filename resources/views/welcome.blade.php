<x-guest-layout>
    <div class="text-center">
        <h1 class="display-1 fw-bold text-primary mb-4">
            {{ config('app.name', 'Laravel') }}
        </h1>
        <p class="lead mb-5">
            {{ __('Пройдите тестирование по Laravel') }}
        </p>

        <div class="mt-5">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-5">
                    {{ __('Перейти в панель') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                    {{ __('Начать') }}
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">
                    {{ __('Регистрация') }}
                </a>
            @endauth
        </div>
    </div>
</x-guest-layout>
