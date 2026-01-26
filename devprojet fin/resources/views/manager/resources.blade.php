@extends('layouts.manager')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Mes Ressources Supervisées</h1>
        <p class="page-subtitle">Liste des ressources dont vous êtes responsable.</p>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Réservation Actuelle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                <tr>
                    <td style="font-weight: 500;">
                        {{ $resource->name }}
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $resource->location }}</div>
                    </td>
                    <td>{{ $resource->category->name }}</td>
                    <td>
                        @if($resource->status == 'active')
                            <span class="status-badge status-approved">Actif</span>
                        @elseif($resource->status == 'maintenance')
                            <span class="status-badge status-pending">Maintenance</span>
                        @else
                            <span class="status-badge status-rejected">Hors Service</span>
                        @endif
                    </td>
                    <td>
                        <!-- TODO: Implement relationship for Active Reservation if needed -->
                        <span style="color: var(--text-muted);">-</span>
                    </td>
                    <td>
                        <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;" disabled>Détails</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <p>Vous ne supervisez aucune ressource pour le moment.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 20px;">
    {{ $resources->links() }}
</div>
@endsection
