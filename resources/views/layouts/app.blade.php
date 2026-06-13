<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO Meta --}}
    <title>{{ $meta['title'] ?? config('app.name') }}</title>
    <meta name="description" content="{{ $meta['description'] ?? '' }}">
    @if(isset($meta['canonical']))
    <link rel="canonical" href="{{ $meta['canonical'] }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $meta['title'] ?? config('app.name') }}">
    <meta property="og:description" content="{{ $meta['description'] ?? '' }}">
    <meta property="og:url" content="{{ $meta['canonical'] ?? url()->current() }}">
    @if(isset($meta['og_image']))
    <meta property="og:image" content="{{ $meta['og_image'] }}">
    @endif
    <meta property="og:site_name" content="{{ $meta['site_name'] ?? config('app.name') }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="{{ $meta['twitter_card'] ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $meta['title'] ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $meta['description'] ?? '' }}">
    @if(isset($meta['og_image']))
    <meta name="twitter:image" content="{{ $meta['og_image'] }}">
    @endif

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
<body>

{{-- Header Ad --}}
<div class="ad-zone ad-header">
    {!! app(\App\Services\AdManager::class)->renderSlot('header') !!}
</div>

{{-- Navigation --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="main-nav">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <span class="brand-icon">⚡</span>
            {{ config('app.name', 'SportStream') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-fill"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') && request('sport') ? 'active' : '' }}" href="{{ route('home') }}?status=live">
                        <i class="bi bi-broadcast"></i> <span class="live-dot"></span> Live
                    </a>
                </li>
                @php
                    $navItems = \App\Models\NavigationMenu::active()->forLocation('header')->rootLevel()->with('children')->get();
                @endphp
                @foreach($navItems as $item)
                    @if($item->children->count())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                @if($item->icon)<i class="bi bi-{{ $item->icon }}"></i> @endif
                                {{ $item->label }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                @foreach($item->children as $child)
                                    <li><a class="dropdown-item" href="{{ $child->url }}" target="{{ $child->target }}">
                                        @if($child->icon)<i class="bi bi-{{ $child->icon }}"></i> @endif
                                        {{ $child->label }}
                                    </a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $item->url }}" target="{{ $item->target }}">
                                @if($item->icon)<i class="bi bi-{{ $item->icon }}"></i> @endif
                                {{ $item->label }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- Search --}}
            <form class="d-flex me-3" action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <input class="form-control search-input" type="search" name="q"
                           value="{{ request('q') }}" placeholder="Search matches, teams...">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            @auth
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                        @if(auth()->user()->is_admin)
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Admin Panel</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- Breadcrumbs --}}
@hasSection('breadcrumbs')
<div class="breadcrumb-bar">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 py-2">
                @yield('breadcrumbs')
            </ol>
        </nav>
    </div>
</div>
@endif

{{-- Main Content --}}
<main id="main-content">
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
    {{-- Footer Ad --}}
    <div class="ad-zone ad-footer">
        {!! app(\App\Services\AdManager::class)->renderSlot('footer') !!}
    </div>

    <div class="footer-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-brand">⚡ {{ config('app.name', 'SportStream') }}</h5>
                    <p class="text-muted">Your #1 destination for live sports streaming. Watch matches from around the world.</p>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-telegram"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('search') }}">Search Matches</a></li>
                        @foreach(\App\Models\SitePage::active()->where('show_in_nav', true)->orderBy('sort_order')->limit(5)->get() as $p)
                            <li><a href="{{ route('page.show', $p->slug) }}">{{ $p->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3">Footer Menu</h6>
                    <ul class="list-unstyled footer-links">
                        @foreach(\App\Models\NavigationMenu::active()->forLocation('footer')->get() as $fi)
                            <li><a href="{{ $fi->url }}" target="{{ $fi->target }}">{{ $fi->label }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <p class="text-muted mb-0 small">&copy; {{ date('Y') }} {{ config('app.name', 'SportStream') }}. All rights reserved.</p>
                <p class="text-muted mb-0 small">For entertainment purposes only. We do not host any streams.</p>
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