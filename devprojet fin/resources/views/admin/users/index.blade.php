@extends('layouts.admin')

@section('header')
    Gestion des Utilisateurs
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1>Utilisateurs</h1>
        <p class="text-muted">Gérez les comptes utilisateurs, leurs rôles et leurs permissions.</p>
    </div>
    <div class="page-header-actions">
        <!-- Optional: Add user button could go here if implemented -->
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Utilisateurs</h3>
        <div class="card-actions">
            <span class="badge badge-indigo">{{ $users->total() }} utilisateurs</span>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Date d'inscription</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-sm {{ $loop->even ? 'avatar-indigo' : 'avatar-blue' }}">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-dark">{{ $user->name }}</div>
                                <div class="text-sm text-muted">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->role->name === 'admin' ? 'badge-purple' : 'badge-blue' }}">
                            {{ ucfirst($user->role->name ?? 'Utilisateur') }}
                        </span>
                    </td>
                    <td>
                        @if($user->status === 'active')
                            <span class="badge badge-green">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Actif
                            </span>
                        @else
                            <span class="badge badge-red">
                                <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Inactif
                            </span>
                        @endif
                    </td>
                    <td class="text-muted">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="text-right">
                        <div class="d-flex justify-content-end gap-2">
                            <!-- Edit Button -->
                            <button class="btn-icon" title="Modifier">
                                <i data-lucide="edit-2"></i>
                            </button>
                            
                            <!-- Delete/Block Button -->
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon text-red" title="Supprimer">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i data-lucide="users"></i>
                            </div>
                            <h3>Aucun utilisateur trouvé</h3>
                            <p class="text-muted">Il n'y a pas encore d'utilisateurs enregistrés.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
