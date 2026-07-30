<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4">Забыли пароль?</h2>
        <p class="text-muted">Введите ваш email и мы отправим ссылку для сброса пароля</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Отправить ссылку
            </button>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">
                Вернуться к входу
            </a>
        </div>
    </form>
</x-guest-layout>
