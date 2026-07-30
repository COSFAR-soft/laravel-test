<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
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
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-4px);
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
</body>
</html>
