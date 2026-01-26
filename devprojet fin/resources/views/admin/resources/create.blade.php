@extends('layouts.admin')

@section('header')
    Nouvelle Ressource
@endsection

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la liste
    </a>
</div>

<div class="card max-w-3xl">
    <div class="card-header">
        <h3 class="card-title">Ajouter une ressource</h3>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.resources.store') }}" method="POST">
            @csrf

            <!-- Informations Générales -->
            <div class="mb-8">
                <h4 class="font-medium text-lg mb-4 text-dark border-b pb-2">Informations Générales</h4>
                
                <div class="form-group">
                    <label class="form-label">Nom de la ressource <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: Serveur Alpha-01">
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Statut Initial <span class="text-red-500">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active">Actif</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="hors_service">Hors Service</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Description détaillée..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Localisation Physique</label>
                    <div class="input-group">
                        <span class="input-group-text"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                        <input type="text" name="location" class="form-control" placeholder="Ex: Salle B, Baie 4, U-12">
                    </div>
                </div>
            </div>

            <!-- Spécifications Techniques -->
            <div class="mb-8">
                <h4 class="font-medium text-lg mb-4 text-dark border-b pb-2">Spécifications Techniques</h4>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">CPU (Cores)</label>
                        <input type="number" name="cpu_cores" class="form-control" min="1" placeholder="Ex: 16">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">RAM (GB)</label>
                        <input type="number" name="ram_gb" class="form-control" min="1" placeholder="Ex: 64">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Stockage (TB)</label>
                        <input type="number" name="storage_tb" class="form-control" step="0.1" min="0" placeholder="Ex: 2.5">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Système d'exploitation</label>
                        <input type="text" name="os_name" class="form-control" placeholder="Ex: Ubuntu 22.04 LTS">
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
                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6 pt-6 border-t">
                <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Enregistrer la ressource
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
