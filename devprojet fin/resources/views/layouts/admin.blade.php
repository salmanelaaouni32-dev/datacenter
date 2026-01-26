<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KAS') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="theme-dark">
    
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand-logo">
                <span class="brand-highlight">KAS</span> ADMIN
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>

            <div class="nav-section-label">Gestion</div>

            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i data-lucide="users"></i> Utilisateurs
            </a>

            <a href="{{ route('admin.resources.index') }}" class="nav-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                <i data-lucide="box"></i> Ressources
            </a>

            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i data-lucide="tags"></i> Catégories
            </a>

            <a href="{{ route('admin.reservations.index') }}" class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                <i data-lucide="calendar"></i> Réservations
            </a>

            <div class="nav-section-label">Système</div>

            <a href="{{ route('admin.maintenances.index') }}" class="nav-link {{ request()->routeIs('admin.maintenances.*') ? 'active' : '' }}">
                <i data-lucide="wrench"></i> Maintenance
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="user-info">
                    <p class="user-name">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="user-role">Administrateur</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Se déconnecter">
                        <i data-lucide="log-out" style="width: 16px; height: 16px; margin: 0;"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-wrapper">
        <header class="top-header">
            <!-- Mobile Toggle would go here -->
            <div class="page-title">
                @yield('header', 'Admin Space')
            </div>
            
            <div class="flex items-center gap-4">
                 <!-- Notifications Icon could go here -->
                 <div style="color: var(--secondary-500); cursor: pointer;">
                    <i data-lucide="bell"></i>
                 </div>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <i data-lucide="check-circle" style="width: 20px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <i data-lucide="alert-circle" style="width: 20px;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
