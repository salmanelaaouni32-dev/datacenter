@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="page-title">Tableau de bord</h1>
        <p class="text-muted mt-2">Aperçu global de l'activité du système.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('mail_info'))
    <div class="alert alert-info">{{ session('mail_info') }}</div>
@endif

<!-- Stats Grid -->
<div class="card-grid">
    <!-- Card 1: Users -->
    <div class="stat-card">
        <div class="stat-icon icon-indigo">
            <i data-lucide="users"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['totalUsers'] }}</h3>
            <p>Total Utilisateurs</p>
        </div>
    </div>

    <!-- Card 2: Resources -->
    <div class="stat-card">
        <div class="stat-icon icon-emerald">
            <i data-lucide="box"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['totalResources'] }}</h3>
            <p>Ressources Totales</p>
        </div>
    </div>

    <!-- Card 3: Active Reservations -->
    <div class="stat-card">
        <div class="stat-icon icon-blue">
            <i data-lucide="calendar"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['activeReservations'] }}</h3>
            <p>Réservations Actives</p>
        </div>
    </div>

    <!-- Card 4: Incidents -->
    <div class="stat-card">
        <div class="stat-icon icon-red">
            <i data-lucide="alert-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['pendingIncidents'] }}</h3>
            <p>Incidents En Cours</p>
        </div>
    </div>
    
     <!-- Card 5: Categories -->
    <div class="stat-card">
        <div class="stat-icon icon-purple">
            <i data-lucide="tags"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['totalCategories'] }}</h3>
            <p>Catégories</p>
        </div>
    </div>

    <!-- Card 6: Pending Reservations -->
    <div class="stat-card">
        <div class="stat-icon icon-orange">
            <i data-lucide="clock"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['pendingReservations'] }}</h3>
            <p>Réservations en attente</p>
        </div>
    </div>
</div>

<div class="flex gap-6 flex-col" style="@media (min-width: 1024px) { flex-direction: row; }">
    <!-- Notifications -->
    <div class="card" style="flex: 2; min-width: 300px;">
        <div class="card-header">
            <div class="card-title">Notifications Récentes</div>
            <a href="#" class="text-sm text-muted">Voir tout</a>
        </div>
        <div class="card-body p-0">
            @if(auth()->user()->unreadNotifications->count() > 0)
                <ul class="divide-y divide-secondary-100">
                    @foreach(auth()->user()->unreadNotifications->take(5) as $notif)
                        <li class="flex gap-4 p-4 border-b border-secondary-100 last:border-0 hover:bg-secondary-50 transition-colors">
                            <div class="text-primary-600 mt-1"><i data-lucide="bell" style="width: 18px;"></i></div>
                            <div>
                                <div class="font-medium text-sm text-secondary-900">{{ $notif->data['message'] ?? 'Notification' }}</div>
                                <div class="text-muted text-xs mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-8 text-center text-muted">
                    <div class="mb-3 inline-flex p-3 rounded-full bg-secondary-50">
                        <i data-lucide="bell-off" class="text-secondary-400"></i>
                    </div>
                    <p>Aucune nouvelle notification.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Role Summary -->
    <div class="card" style="flex: 1; min-width: 300px;">
        <div class="card-header">
             <div class="card-title">Répartition Utilisateurs</div>
        </div>
        <div class="card-body">
            <ul class="w-full">
                @foreach($stats['usersByRole'] as $role => $count)
                <li class="flex justify-between items-center py-3 border-b border-secondary-100 last:border-0">
                    <div class="flex items-center gap-3">
                         <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                         <span class="text-sm font-medium text-secondary-700">{{ $role }}</span>
                    </div>
                    <span class="badge badge-gray">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- Account Requests Section -->
<div class="card mt-6">
    <div class="card-header">
        <div class="card-title">Demandes de création de compte</div>
        <span class="badge badge-orange">{{ $pendingRequests->count() }} en attente</span>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle souhaité</th>
                    <th>Justification</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRequests as $request)
                <tr>
                    <td><div class="font-medium">{{ $request->name }}</div></td>
                    <td>{{ $request->email }}</td>
                    <td><span class="badge badge-blue">{{ ucfirst($request->role) }}</span></td>
                    <td class="text-muted text-sm">{{ Str::limit($request->justification, 50) }}</td>
                    <td class="text-muted text-sm">{{ $request->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.account-requests.approve', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Accepter</button>
                            </form>
                            <form action="{{ route('admin.account-requests.reject', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Refuser</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-muted">
                        <i data-lucide="user-check" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        Aucune demande de compte en attente.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pending Reservations Section -->
<div class="card mt-6">
    <div class="card-header">
        <div class="card-title">Demandes de Réservation en Attente</div>
        <span class="badge badge-orange">{{ $pendingReservationsList->count() }} en attente</span>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Ressource</th>
                    <th>Période</th>
                    <th>Motif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingReservationsList as $reservation)
                <tr>
                    <td>
                        <div class="font-medium">{{ $reservation->user->name }}</div>
                        <div class="text-sm text-muted">{{ $reservation->user->email }}</div>
                    </td>
                    <td>
                        <div class="font-medium">{{ $reservation->resource->name }}</div>
                    </td>
                    <td>
                        <div class="text-sm">Du: {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</div>
                        <div class="text-sm">Au: {{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="text-muted text-sm">{{ Str::limit($reservation->reason, 50) }}</td>
                    <td>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                            </form>
                            <form action="{{ route('admin.reservations.reject', $reservation->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">Refuser</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-muted">
                        <i data-lucide="calendar-check" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                        Aucune réservation en attente.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Reservations Section -->
<div class="card mt-6">
    <div class="card-header">
        <div class="card-title">Réservations Récentes</div>
        <a href="{{ route('admin.reservations.index') }}" class="text-sm text-muted">Voir toutes les réservations &rarr;</a>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Ressource</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentReservations as $reservation)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $reservation->user->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $reservation->user->email }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $reservation->resource->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $reservation->resource->category->name ?? 'N/A' }}</div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($reservation->status == 'pending')
                            <span class="status-badge status-pending">En attente</span>
                        @elseif($reservation->status == 'approved')
                            <span class="status-badge status-approved">Approuvée</span>
                        @elseif($reservation->status == 'active')
                            <span class="status-badge status-active">En cours</span>
                        @elseif($reservation->status == 'completed')
                            <span class="status-badge status-closed">Terminée</span>
                        @elseif($reservation->status == 'cancelled')
                            <span class="status-badge status-rejected">Annulée</span>
                        @elseif($reservation->status == 'rejected')
                            <span class="status-badge status-rejected">Refusée</span>
                        @endif
                    </td>
                    <td>
                        @if($reservation->status == 'pending')
                            <div style="display: flex; gap: 8px;">
                                <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" style="padding: 6px 12px; font-size: 0.85rem;">Approuver</button>
                                </form>
                                <form action="{{ route('admin.reservations.reject', $reservation->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;">Refuser</button>
                                </form>
                            </div>
                        @else
                            <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i data-lucide="calendar" style="margin-bottom: 8px; width: 32px; height: 32px; color: #cbd5e1;"></i>
                        <p>Aucune réservation récente.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
