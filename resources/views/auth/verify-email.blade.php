<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4">Подтверждение email</h2>
        <p class="text-muted">Пожалуйста, подтвердите ваш email</p>
    </div>

    <div class="alert alert-info">
        Благодарим за регистрацию! Прежде чем начать, пожалуйста, подтвердите свой email, перейдя по ссылке, которую мы отправили вам на почту.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            Новая ссылка для подтверждения была отправлена на ваш email.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Отправить ссылку повторно
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-outline-secondary">
                Выйти
            </button>
        </div>
    </form>
</x-guest-layout>
