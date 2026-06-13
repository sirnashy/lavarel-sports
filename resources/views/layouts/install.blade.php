<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Installation - {{ config('app.name', 'SportStream') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('head')
</head>
<body class="d-flex flex-column min-vh-100">

{{-- Navigation --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="main-nav">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <span class="brand-icon">⚡</span>
            {{ config('app.name', 'SportStream') }}
        </a>
    </div>
</nav>

{{-- Main Content --}}
<main id="main-content" class="flex-grow-1">
    @if(session('success'))
        <div class="container-fluid mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container-fluid mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

{{-- Footer --}}
<footer class="site-footer mt-auto">
    <div class="footer-main">
        <div class="container-fluid">
            <div class="d-flex justify-content-center align-items-center flex-wrap">
                <p class="text-muted mb-0 small">&copy; {{ date('Y') }} {{ config('app.name', 'SportStream') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
