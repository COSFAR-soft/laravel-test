@props([
    'action' => route('register'),
    'method' => 'POST'
])

<form method="{{ $method === 'POST' ? 'POST' : 'GET' }}" action="{{ $action }}">
    @csrf

    <div class="text-center mb-4">
        <h2 class="h4">{{ __('Регистрация') }}</h2>
        <p class="text-muted">{{ __('Создайте новый аккаунт') }}</p>
    </div>

    <!-- Name -->
    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Имя') }}</label>
        <input id="name" type="text"
               class="form-control @error('name') is-invalid @enderror"
               name="name" value="{{ old('name') }}"
               required autofocus>
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <input id="email" type="email"
               class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}"
               required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">{{ __('Пароль') }}</label>
        <input id="password" type="password"
               class="form-control @error('password') is-invalid @enderror"
               name="password" required>
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">{{ __('Подтверждение пароля') }}</label>
        <input id="password_confirmation" type="password"
               class="form-control"
               name="password_confirmation" required>
    </div>

    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            {{ __('Зарегистрироваться') }}
        </button>
    </div>

    <div class="text-center mt-3">
        <span class="text-muted">{{ __('Уже есть аккаунт?') }}</span>
        <a href="{{ route('login') }}" class="text-decoration-none">
            {{ __('Войти') }}
        </a>
    </div>

    {{ $slot ?? '' }}
</form>
