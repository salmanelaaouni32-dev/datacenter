@extends('layouts.admin')

@section('header')
    Modifier la Catégorie
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Modifier la Catégorie</h1>
        <p class="text-muted">Mettez à jour les informations de la catégorie.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Retour
        </a>
    </div>
</div>

<div class="card max-w-2xl mx-auto">
    <div class="card-body">
         <h2 class="text-lg font-medium text-dark mb-4">Modifier la Catégorie: {{ $category->name }}</h2>

        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nom de la catégorie <span class="text-red-500">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i data-lucide="tag" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ $category->description }}</textarea>
            </div>

            <div class="flex justify-end pt-6 border-t mt-6">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary mr-2">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
