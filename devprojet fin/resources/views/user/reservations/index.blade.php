@extends('layouts.user')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Mes Réservations</h1>
        <p class="page-subtitle">Historique complet de vos demandes de ressources.</p>
    </div>
    <a href="{{ route('internal.catalogue') }}" class="btn btn-primary">
        <i data-lucide="plus" style="width: 18px; margin-right: 8px;"></i> Nouvelle demande
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Ressource</th>
                    <th>Période</th>
                    <th>Motif</th>
                    <th>Date de demande</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $reservation->resource->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $reservation->resource->category->name ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.9rem;">
                            Du {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}
                        </div>
                        <div style="font-size: 0.9rem; color: var(--text-muted);">
                            Au {{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td>
                        <span style="color: var(--text-muted); font-size: 0.9rem;">
                            {{ Str::limit($reservation->reason, 30) ?: '-' }}
                        </span>
                    </td>
                    <td>
                        {{ $reservation->created_at->format('d/m/Y') }}
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
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <p>Aucune réservation trouvée.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 20px;">
    {{ $reservations->links() }}
</div>
@endsection
