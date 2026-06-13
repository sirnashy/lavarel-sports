<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | {{ config('app.name') }} Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body class="admin-body">

<div class="admin-wrapper">
    {{-- Sidebar --}}
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <span class="brand-icon">⚡</span>
                <span class="brand-name">{{ config('app.name') }}</span>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <span class="nav-label">Main</span>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('home') }}" class="nav-item" target="_blank">
                    <i class="bi bi-globe"></i> View Site
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-label">Content</span>
                <a href="{{ route('admin.featured.index') }}" class="nav-item {{ request()->routeIs('admin.featured*') ? 'active' : '' }}">
                    <i class="bi bi-star"></i> Featured Matches
                </a>
                <a href="{{ route('admin.pages.index') }}" class="nav-item {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Pages
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-label">Monetization</span>
                <a href="{{ route('admin.advertisements.index') }}" class="nav-item {{ request()->routeIs('admin.advertisements*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone"></i> Advertisements
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-label">Users</span>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Users
                </a>
                <a href="{{ route('admin.logs.index') }}" class="nav-item {{ request()->routeIs('admin.logs*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> Activity Logs
                </a>
            </div>

            <div class="nav-section">
                <span class="nav-label">Configuration</span>
                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Site Settings
                </a>
                <a href="{{ route('admin.settings.seo') }}" class="nav-item {{ request()->routeIs('admin.settings.seo') ? 'active' : '' }}">
                    <i class="bi bi-search"></i> SEO Settings
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <i class="bi bi-person-circle"></i>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role text-muted small">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100 mt-2">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="admin-main">
        {{-- Top Bar --}}
        <header class="admin-topbar">
            <button class="sidebar-toggle" id="sidebar-toggle">
                <i class="bi bi-list"></i>
            </button>
            <h4 class="page-title mb-0">@yield('title', 'Dashboard')</h4>
            <div class="topbar-actions ms-auto">
                @yield('actions')
            </div>
        </header>

        {{-- Alerts --}}
        <div class="admin-alerts">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        {{-- Content --}}
        <div class="admin-content">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
    document.getElementById('admin-sidebar').classList.toggle('collapsed');
});
</script>
@stack('scripts')
</body>
</html>