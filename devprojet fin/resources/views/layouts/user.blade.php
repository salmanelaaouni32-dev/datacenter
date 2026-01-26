<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KAS - User Space') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body class="theme-dark">
    <nav class="navbar">
        <div class="nav-brand">
            <i data-lucide="server" style="color: var(--primary-color);"></i>
            <span>KAS User</span>
        </div>
        
        <ul class="nav-links">
            <li>
                <a href="{{ route('internal.dashboard') }}" class="nav-link {{ request()->routeIs('internal.dashboard') ? 'active' : '' }}">
                    Tableau de Bord
                </a>
            </li>
            <li>
                <a href="{{ route('internal.catalogue') }}" class="nav-link {{ request()->routeIs('internal.catalogue') ? 'active' : '' }}">
                    Catalogue
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}" style="display: flex; align-items: center;">
                    Notifications
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span style="background-color: #ef4444; color: white; padding: 2px 6px; border-radius: 9999px; font-size: 0.7rem; margin-left: 6px; line-height: 1;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('internal.reservations') }}" class="nav-link {{ request()->routeIs('internal.reservations') ? 'active' : '' }}">
                    Mes Réservations
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
                    <i data-lucide="log-out" style="width: 16px; margin-right: 4px; vertical-align: text-bottom;"></i> Déconnexion
                </button>
            </form>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
