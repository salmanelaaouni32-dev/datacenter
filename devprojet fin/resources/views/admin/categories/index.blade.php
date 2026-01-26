@extends('layouts.admin')

@section('header')
    Gestion des Catégories
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Catégories</h1>
        <p class="text-muted">Définissez les types de ressources (Serveurs, Routeurs, IoT, etc.).</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Nouvelle Catégorie
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Catégories</h3>
        <div class="card-actions">
            <span class="badge badge-indigo">Total: {{ $categories->total() }}</span>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Utilisation</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="avatar avatar-xs avatar-indigo mr-3">
                                <i data-lucide="tag" class="w-3 h-3"></i>
                            </div>
                            <span class="font-medium text-dark">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted">
                        {{ Str::limit($category->description, 60) ?: 'Aucune description' }}
                    </td>
                    <td>
                        <span class="badge {{ $category->resources_count > 0 ? 'badge-blue' : 'badge-gray' }}">
                            {{ $category->resources_count }} ressource(s)
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-icon" title="Modifier">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            
                            @if($category->resources_count == 0)
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon text-red-500" title="Supprimer">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @else
                                <span class="text-muted cursor-not-allowed p-2" title="Impossible de supprimer : catégorie utilisée">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6">
                        <div class="flex flex-col items-center justify-center">
                            <div class="avatar avatar-lg avatar-indigo mb-4">
                                <i data-lucide="tags" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-lg font-medium text-dark mb-2">Aucune catégorie</h3>
                            <p class="text-muted mb-4">Créez des catégories pour organiser vos ressources.</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Créer une catégorie
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="card-footer">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
