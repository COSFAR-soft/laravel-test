{{-- resources/views/admin/layouts/admin.blade.php --}}
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel')) - Админ панель</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
<div class="d-flex" id="wrapper">
    <div class="bg-dark text-white" id="sidebar-wrapper" style="min-width:250px;min-height:100vh;">
        <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
            <i class="bi bi-gear"></i> Админка
        </div>
        <div class="list-group list-group-flush my-3">
            <a href="{{ route('admin.tests.index') }}" class="list-group-item list-group-item-action bg-transparent text-white">
                <i class="bi bi-list-ul"></i> Тесты
            </a>
            <a href="{{ route('admin.tests.create') }}" class="list-group-item list-group-item-action bg-transparent text-white">
                <i class="bi bi-plus-circle"></i> Создать тест
            </a>
            <hr class="text-secondary">
            <a href="{{ route('admin.dashboard.index') }}" class="list-group-item list-group-item-action bg-transparent text-white">
                <i class="bi bi-graph-up"></i> Статистика
            </a>
            <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-transparent text-danger">
                <i class="bi bi-x-circle"></i> Закрыть панель
            </a>
        </div>
    </div>
    <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle"><i class="bi bi-list"></i></button>
                <span class="navbar-text ms-3"><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</span>
                <div class="ms-auto">
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Выйти
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </nav>
        <div class="container-fluid px-4 py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
<script>
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>
<style>
    #wrapper.toggled #sidebar-wrapper{margin-left:-250px}
    #page-content-wrapper{transition:all 0.3s}
    .list-group-item{border:none;border-radius:0 !important}
    .list-group-item:hover{background-color:rgba(255,255,255,0.1) !important}
</style>
</body>
</html>
