@extends('layouts.admin')

@section('header')
    Planifier une Maintenance
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Planifier une Maintenance</h1>
        <p class="text-muted">Définissez les détails de l'intervention technique.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.maintenances.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Retour
        </a>
    </div>
</div>

<div class="card max-w-2xl mx-auto">
    <div class="card-body">
        <form action="{{ route('admin.maintenances.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Ressource concernée <span class="text-red-500">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i data-lucide="server" class="w-4 h-4"></i>
                    </span>
                    <select name="resource_id" class="form-control" required>
                        <option value="">-- Sélectionner une ressource --</option>
                        @foreach($resources as $resource)
                            <option value="{{ $resource->id }}">
                                {{ $resource->name }} ({{ $resource->category->name }}) - {{ ucfirst($resource->status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Motif de la maintenance <span class="text-red-500">*</span></label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="Ex: Mise à jour système, Remplacement disque dur..."></textarea>
            </div>

            <div class="grid-2 gap-4">
                <div class="form-group">
                    <label class="form-label">Date et Heure de Début <span class="text-red-500">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </span>
                        <input type="datetime-local" name="start_time" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Date et Heure de Fin (Estimée)</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i data-lucide="calendar-check" class="w-4 h-4"></i>
                        </span>
                        <input type="datetime-local" name="end_time" class="form-control">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t mt-6">
                <a href="{{ route('admin.maintenances.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Planifier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
