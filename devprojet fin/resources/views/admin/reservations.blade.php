@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="page-title">Gestion des Réservations</h1>
        <p class="text-muted mt-2">Visualisez et gérez toutes les réservations du système.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Toutes les Réservations</div>
        <div class="flex items-center gap-3">
            <select class="form-input" style="width: auto;" onchange="window.location.href='{{ route('admin.reservations.index') }}?status=' + this.value">
                <option value="">Tous les statuts</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvée</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>En cours</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Refusée</option>
            </select>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Ressource</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Statut</th>
                    <th>Motif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $reservation)
                <tr>
                    <td>#{{ $reservation->id }}</td>
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
                        <span style="color: var(--text-muted); font-size: 0.9rem;">
                            {{ $reservation->reason ?? 'Aucun motif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            @if($reservation->status == 'pending')
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
                            @else
                                <span style="color: var(--text-muted); font-size: 0.85rem;">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i data-lucide="calendar" style="margin-bottom: 8px; width: 32px; height: 32px; color: #cbd5e1;"></i>
                        <p>Aucune réservation trouvée.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reservations->hasPages())
    <div class="card-footer">
        {{ $reservations->links() }}
    </div>
    @endif
</div>
@endsection