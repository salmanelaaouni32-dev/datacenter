@extends('layouts.manager')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tableau de Bord Supervision</h1>
        <p class="page-subtitle">Gérez les demandes de réservation et supervisez vos ressources affectées.</p>
    </div>
</div>

<div class="grid-3" style="margin-bottom: 32px;">
    <!-- Stat Cards -->
    <div class="stat-card">
        <div class="stat-icon icon-violet">
            <i data-lucide="inbox"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $pendingRequests->count() }}</h3>
            <p>Demandes en attente</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon icon-emerald">
            <i data-lucide="check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $activeReservationsCount }}</h3>
            <p>Réservations Active</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon icon-blue">
            <i data-lucide="server"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $myResourcesCount }}</h3>
            <p>Ressources supervisées</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon icon-red">
            <i data-lucide="alert-triangle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $pendingIncidentsCount }}</h3>
            <p>Incidents en cours</p>
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
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Demandes en Attente d'Approbation</div>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Ressource</th>
                    <th>Période Demandée</th>
                    <th>Motif</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRequests as $request)
                <tr>
                    <td>
                        <div class="font-semibold">{{ $request->user->name }}</div>
                        <div class="text-sm text-muted">{{ $request->user->email }}</div>
                    </td>
                    <td>
                        {{ $request->resource->name }}
                    </td>
                    <td>
                        <div class="text-sm">
                            <span class="text-muted">Du:</span> {{ \Carbon\Carbon::parse($request->start_time)->format('d/m/Y H:i') }}
                        </div>
                        <div class="text-sm">
                            <span class="text-muted">Au:</span> {{ \Carbon\Carbon::parse($request->end_time)->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td class="text-sm text-muted">
                        {{ Str::limit($request->reason, 50) ?? 'Aucun motif' }}
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <form action="{{ route('manager.reservations.approve', $request->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                            </form>
                            <form action="{{ route('manager.reservations.reject', $request->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">Refuser</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i data-lucide="check-check"></i>
                            <h3>Aucune demande en attente</h3>
                            <p>Toutes les demandes de réservation ont été traitées.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection