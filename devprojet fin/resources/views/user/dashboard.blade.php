@extends('layouts.user')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Bienvenue, {{ Auth::user()->name }}</h1>
        <p class="page-subtitle">Aperçu de vos activités récentes et ressources.</p>
    </div>
    <a href="{{ route('internal.catalogue') }}" class="btn btn-primary">
        <i data-lucide="plus" style="width: 18px; margin-right: 8px;"></i> Nouvelle Réservation
    </a>
</div>

<div class="grid-2" style="margin-bottom: 32px;">
    <!-- Stat Cards -->
    <div class="stat-card">
        <div class="stat-icon icon-blue">
            <i data-lucide="calendar-clock"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $activeReservationsCount }}</h3>
            <p>Réservations Actives/En Attente</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon icon-emerald">
            <i data-lucide="check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $approvedReservationsThisMonth }}</h3>
            <p>Demandes Approuvées ce mois</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon icon-amber">
            <i data-lucide="history"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $totalReservationsCount }}</h3>
            <p>Total Réservations</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-red">
            <i data-lucide="x-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $rejectedReservationsCount }}</h3>
            <p>Demandes Refusées</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span>Vos Dernières Réservations</span>
        <a href="{{ route('internal.reservations') }}" style="font-size: 0.9rem; color: var(--primary-color);">Tout voir &rarr;</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Ressource</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $reservation->resource->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $reservation->resource->category->name ?? 'N/A' }}</div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($reservation->start_time)->diffForHumans(\Carbon\Carbon::parse($reservation->end_time), true) }}
                    </td>
                    <td>
                        @if($reservation->status == 'pending')
                            <span class="status-badge status-pending">En attente</span>
                        @elseif($reservation->status == 'approved')
                            <span class="status-badge status-approved">Approvée</span>
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
                            <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;">Annuler</button>
                        @else
                            <span style="color: var(--text-muted);">-</span>
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
