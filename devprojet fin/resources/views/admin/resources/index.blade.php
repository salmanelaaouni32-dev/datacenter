@extends('layouts.admin')

@section('header')
    Gestion des Ressources
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Ressources</h1>
        <p class="text-muted">Liste complète des ressources du Data Center (Serveurs, Switchs, etc.).</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Nouvelle Ressource
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Ressources</h3>
        <div class="card-actions">
            <span class="badge badge-indigo">Total: {{ $resources->count() }}</span>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Statut</th>
                    <th>Nom / ID</th>
                    <th>Catégorie</th>
                    <th>Spécifications</th>
                    <th>Localisation</th>
                    <th>Manager</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                <tr>
                    <td>
                        @if($resource->status == 'active')
                            <span class="badge badge-green">
                                <i data-lucide="activity" class="w-3 h-3 mr-1"></i> Actif
                            </span>
                        @elseif($resource->status == 'maintenance')
                            <span class="badge badge-yellow">
                                <i data-lucide="wrench" class="w-3 h-3 mr-1"></i> Maintenance
                            </span>
                        @else
                            <span class="badge badge-red">
                                <i data-lucide="power-off" class="w-3 h-3 mr-1"></i> Hors service
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="font-medium text-dark">{{ $resource->name }}</span>
                            <span class="text-xs text-muted">ID: {{ $resource->id }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-blue">{{ $resource->category->name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <div class="text-sm">
                                <span title="CPU" class="mr-2"><i data-lucide="cpu" class="w-3 h-3 inline text-muted"></i> {{ $resource->cpu_cores }} Cores</span>
                                <span title="RAM"><i data-lucide="memory-stick" class="w-3 h-3 inline text-muted"></i> {{ $resource->ram_gb }} GB</span>
                            </div>
                            <div class="text-xs text-muted">
                                {{ $resource->storage_tb }} TB • {{ $resource->os_name }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center text-sm text-dark">
                            <i data-lucide="map-pin" class="w-3 h-3 mr-1 text-muted"></i>
                            {{ $resource->location }}
                        </div>
                    </td>
                    <td>
                        @if($resource->manager)
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-xs avatar-indigo">
                                    {{ substr($resource->manager->name, 0, 1) }}
                                </div>
                                <span class="text-sm">{{ $resource->manager->name }}</span>
                            </div>
                        @else
                            <span class="text-muted text-sm">-</span>
                        @endif
                    </td>
                    <td class="text-right">
                         <a href="{{ route('admin.resources.edit', $resource->id) }}" class="btn-icon" title="Modifier">
                            <i data-lucide="edit-3"></i>
                         </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i data-lucide="box"></i>
                            </div>
                            <h3>Aucune ressource trouvée</h3>
                            <p class="text-muted">Commencez par ajouter une nouvelle ressource au système.</p>
                            <a href="{{ route('admin.resources.create') }}" class="btn btn-primary mt-3">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Ajouter une ressource
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
