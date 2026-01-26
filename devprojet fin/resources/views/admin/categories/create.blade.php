@extends('layouts.admin')

@section('header')
    Nouvelle Catégorie
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Nouvelle Catégorie</h1>
        <p class="text-muted">Créez une nouvelle catégorie pour classer vos ressources.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Retour
        </a>
    </div>
</div>

<div class="card max-w-2xl mx-auto">
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nom de la catégorie <span class="text-red-500">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: Serveurs Physiques">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Description de cette catégorie de ressources..."></textarea>
            </div>

            <div class="flex justify-end pt-6 border-t mt-6">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Créer la catégorie
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
