<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4">Подтверждение пароля</h2>
        <p class="text-muted">Пожалуйста, подтвердите пароль для продолжения</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Подтвердить
            </button>
        </div>
    </form>
</x-guest-layout>
