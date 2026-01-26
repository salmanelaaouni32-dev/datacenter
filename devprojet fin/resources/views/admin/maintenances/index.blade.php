@extends('layouts.admin')

@section('header')
    Gestion des Maintenances
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Maintenances</h1>
        <p class="text-muted">Planifiez et suivez les interventions techniques sur vos ressources.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.maintenances.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Planifier une Maintenance
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Historique des Interventions</h3>
        <div class="card-actions">
            <span class="badge badge-indigo">Total: {{ $maintenances->total() }}</span>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ressource</th>
                    <th>Motif</th>
                    <th>Période</th>
                    <th>Statut</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $maintenance)
                <tr>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-medium text-dark">{{ $maintenance->resource->name }}</span>
                            <span class="text-xs text-muted">{{ $maintenance->resource->category->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-sm text-dark">{{ $maintenance->reason }}</div>
                    </td>
                    <td>
                        <div class="flex flex-col gap-1 text-sm">
                            <div class="flex items-center text-muted">
                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                Début: {{ \Carbon\Carbon::parse($maintenance->start_time)->format('d/m/Y H:i') }}
                            </div>
                            @if($maintenance->end_time)
                            <div class="flex items-center text-muted">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                Fin: {{ \Carbon\Carbon::parse($maintenance->end_time)->format('d/m/Y H:i') }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($maintenance->status == 'scheduled')
                            <span class="badge badge-yellow">
                                <i data-lucide="calendar" class="w-3 h-3 mr-1"></i> Planifiée
                            </span>
                        @elseif($maintenance->status == 'in_progress')
                            <span class="badge badge-blue">
                                <i data-lucide="loader" class="w-3 h-3 mr-1"></i> En cours
                            </span>
                        @elseif($maintenance->status == 'completed')
                            <span class="badge badge-green">
                                <i data-lucide="check" class="w-3 h-3 mr-1"></i> Terminée
                            </span>
                        @else
                            <span class="badge badge-red">
                                <i data-lucide="x" class="w-3 h-3 mr-1"></i> Annulée
                            </span>
                        @endif
                    </td>
                    <td class="text-right">
                         <div class="flex justify-end gap-2">
                             <!-- Placeholder for future actions like Edit or Cancel -->
                             <button class="btn-icon text-muted cursor-not-allowed" title="Détails (Bientôt)">
                                <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6">
                        <div class="flex flex-col items-center justify-center">
                            <div class="avatar avatar-lg avatar-indigo mb-4">
                                <i data-lucide="wrench" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-lg font-medium text-dark mb-2">Aucune maintenance</h3>
                            <p class="text-muted mb-4">Le calendrier de maintenance est vide.</p>
                            <a href="{{ route('admin.maintenances.create') }}" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Planifier
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($maintenances->hasPages())
    <div class="card-footer">
        {{ $maintenances->links() }}
    </div>
    @endif
</div>
@endsection
