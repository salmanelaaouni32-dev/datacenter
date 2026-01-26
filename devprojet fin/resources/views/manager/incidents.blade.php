@extends('layouts.manager')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Gestion des Incidents</h1>
        <p class="page-subtitle">Suivi et résolution des incidents signalés sur vos ressources.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Liste des Incidents
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Ressource</th>
                    <th>Signalé par</th>
                    <th>Description</th>
                    <th>Priorité</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $incident->resource->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $incident->resource->category->name ?? '' }}</div>
                    </td>
                    <td>
                        <div>{{ $incident->user->name }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $incident->user->email }}</div>
                    </td>
                    <td>
                        <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $incident->description }}">
                            {{ $incident->description }}
                        </div>
                    </td>
                    <td>
                        @if($incident->priority == 'high')
                            <span class="badge badge-danger">Haute</span>
                        @elseif($incident->priority == 'medium')
                            <span class="badge badge-warning">Moyenne</span>
                        @else
                            <span class="badge badge-info">Basse</span>
                        @endif
                    </td>
                    <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($incident->status == 'open')
                            <span class="badge badge-danger">Ouvert</span>
                        @elseif($incident->status == 'in_progress')
                            <span class="badge badge-warning">En cours</span>
                        @elseif($incident->status == 'resolved')
                            <span class="badge badge-success">Résolu</span>
                        @endif
                    </td>
                    <td>
                        @if($incident->status != 'resolved')
                        <form action="{{ route('manager.incidents.resolve', $incident->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">
                                <i data-lucide="check" style="width: 16px; height: 16px; margin-right: 4px;"></i> Résoudre
                            </button>
                        </form>
                        @else
                        <span class="text-muted"><i data-lucide="check-circle" style="width: 16px;"></i></span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Aucun incident signalé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    {{ $incidents->links() }}
</div>
@endsection
