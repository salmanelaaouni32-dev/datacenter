<nav class="user-nav">
    <span class="user-info">
        Bienvenue, {{ Auth::user()->name }} ({{ Auth::user()->role->name }})
    </span>

    <ul>
        @php
            $role = Auth::user()->role->name;
        @endphp

        <li><a href="{{ route('home') }}">Tableau de Bord</a></li>

        {{-- Liens ADMINISTRATEUR --}}
        @if ($role === 'Administrateur')
            <li><a href="{{ route('admin.resource-management.index') }}">Gérer Ressources</a></li>
            <li><a href="{{ route('admin.users.index') }}">Gérer Utilisateurs</a></li>
            <li><a href="{{ route('admin.maintenance.index') }}">Maintenance</a></li>
            <li><a href="{{ route('admin.stats.global') }}">Statistiques</a></li>
        @endif

        {{-- Liens RESPONSABLE TECHNIQUE --}}
        @if ($role === 'Responsable Technique')
            <li><a href="{{ route('manager.reservations.pending') }}">Approbations</a></li>
            <li><a href="{{ route('manager.incidents.index') }}">Incidents</a></li>
        @endif

        {{-- Liens UTILISATEUR INTERNE --}}
        @if ($role === 'Utilisateur Interne')
            <li><a href="{{ route('internal.reservations.index') }}">Mes Réservations</a></li>
            <li><a href="{{ route('resource.list') }}">Réserver une Ressource</a></li>
            <li><a href="#">Signaler un Problème</a></li>
        @endif
        
        <li>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Déconnexion</button>
            </form>
        </li>
    </ul>
</nav>