<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4">{{ __('Login') }}</h2>
        <p class="text-muted">{{ __('Sign in to your account') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}"
                   required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                {{ __('Log in') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none">
                    {{ __('Forgot your password?') }}
                </a>
            </div>
        @endif

        @if (Route::has('register'))
            <div class="text-center mt-2">
                <span class="text-muted">{{ __("Don't have an account?") }}</span>
                <a href="{{ route('register') }}" class="text-decoration-none">
                    {{ __('Register') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
