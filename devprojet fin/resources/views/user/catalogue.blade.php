@extends('layouts.user')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Catalogue des Ressources</h1>
        <p class="page-subtitle">Explorez et réservez les ressources disponibles.</p>
    </div>
</div>

<div class="card" style="margin-bottom: 32px; padding: 16px;">
    <!-- Filter Placeholder -->
    <div style="display: flex; gap: 16px;">
        <input type="text" placeholder="Rechercher une ressource..." class="form-control" style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;">
        <select class="form-control" style="padding: 10px; border: 1px solid var(--border-color); border-radius: 6px;">
            <option value="">Toutes les catégories</option>
            <!-- Dynamic categories here later -->
        </select>
        <button class="btn btn-primary">Filtrer</button>
    </div>
</div>

<div class="grid-3">
    @forelse($resources as $resource)
    <div class="card resource-card">
        <div class="resource-img">
            @if(Str::contains(strtolower($resource->category->name ?? ''), 'serveur'))
                <i data-lucide="server" style="width: 48px; height: 48px;"></i>
            @elseif(Str::contains(strtolower($resource->category->name ?? ''), 'stockage'))
                <i data-lucide="hard-drive" style="width: 48px; height: 48px;"></i>
            @else
                <i data-lucide="box" style="width: 48px; height: 48px;"></i>
            @endif
        </div>
        <div class="resource-details">
            <div class="resource-name">{{ $resource->name }}</div>
            <div class="resource-meta">{{ $resource->category->name ?? 'Non classé' }}</div>
            
            <div style="margin-bottom: 16px;">
                <div class="resource-specs">
                    <span><i data-lucide="cpu" style="width: 14px; vertical-align: middle;"></i> {{ $resource->cpu_cores }} Cores</span>
                    <span><i data-lucide="memory-stick" style="width: 14px; vertical-align: middle;"></i> {{ $resource->ram_gb }} GB</span>
                </div>
            </div>

            <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $resource->description ?? 'Aucune description disponible.' }}
            </p>

            @if($resource->status === 'active')
                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn btn-primary" style="width: 100%;">
                    Réserver
                </a>
            @else
                <button disabled class="btn btn-secondary" style="width: 100%; cursor: not-allowed; opacity: 0.6;">
                    Indisponible ({{ ucfirst($resource->status) }})
                </button>
            @endif
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: var(--text-muted);">
        <i data-lucide="search-x" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
        <p style="font-size: 1.1rem; font-weight: 500;">Aucune ressource disponible pour le moment.</p>
    </div>
    @endforelse
</div>
@endsection
