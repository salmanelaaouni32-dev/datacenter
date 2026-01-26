@extends('layouts.admin')

@section('header')
    Modifier la Ressource
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la liste
    </a>
</div>

<div class="card max-w-3xl">
    <div class="card-header">
        <h3 class="card-title">Modifier: {{ $resource->name }}</h3>
        <div class="card-actions">
            <span class="badge badge-gray">ID: {{ $resource->id }}</span>
        </div>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.resources.update', $resource->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informations Générales -->
            <div class="mb-8">
                <h4 class="font-medium text-lg mb-4 text-dark border-b pb-2">Informations Générales</h4>
                
                <div class="form-group">
                    <label class="form-label">Nom de la ressource <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ $resource->name }}" required>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $resource->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Statut <span class="text-red-500">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ $resource->status == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="maintenance" {{ $resource->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="hors_service" {{ $resource->status == 'hors_service' ? 'selected' : '' }}>Hors Service</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $resource->description }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Localisation Physique</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                        <input type="text" name="location" class="form-control" value="{{ $resource->location }}">
                    </div>
                </div>
            </div>

            <!-- Spécifications Techniques -->
            <div class="mb-8">
                <h4 class="font-medium text-lg mb-4 text-dark border-b pb-2">Spécifications Techniques</h4>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">CPU (Cores)</label>
                        <input type="number" name="cpu_cores" class="form-control" min="1" value="{{ $resource->cpu_cores }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">RAM (GB)</label>
                        <input type="number" name="ram_gb" class="form-control" min="1" value="{{ $resource->ram_gb }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Stockage (TB)</label>
                        <input type="number" name="storage_tb" class="form-control" step="0.1" min="0" value="{{ $resource->storage_tb }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Système d'exploitation</label>
                        <input type="text" name="os_name" class="form-control" value="{{ $resource->os_name }}">
                    </div>
                </div>
            </div>

            <!-- Management -->
            <div class="mb-6">
                <h4 class="font-medium text-lg mb-4 text-dark border-b pb-2">Gestion</h4>
                
                <div class="form-group">
                    <label class="form-label">Responsable Technique</label>
                    <select name="manager_id" class="form-control">
                        <option value="">-- Sélectionner un responsable --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ $resource->manager_id == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6 pt-6 border-t">
                <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary">Annuler</a>
                
                <form action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Supprimer
                    </button>
                </form>

                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
