<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KAS - Manager Space') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/manager.css') }}">
</head>
<body class="theme-dark">
    <nav class="navbar">
        <div class="nav-brand">
            <i data-lucide="shield-check" style="color: var(--primary-color);"></i>
            <span>KAS Manager</span>
        </div>
        
        <ul class="nav-links">
            <li>
                <a href="{{ route('manager.dashboard') }}" class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                    Supervision
                </a>
            </li>
            <li>
                <a href="{{ route('manager.resources') }}" class="nav-link {{ request()->routeIs('manager.resources') ? 'active' : '' }}">
                    Mes Ressources
                </a>
            </li>
        </ul>

        <div class="nav-user">
            <div style="font-size: 0.9rem; font-weight: 500;">
                {{ Auth::user()->name }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i data-lucide="log-out" style="width: 16px; vertical-align: middle;"></i>
                </button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div style="background-color: #f0fdf4; color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fef2f2; color: #b91c1c; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; border: 1px solid #fecaca;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
