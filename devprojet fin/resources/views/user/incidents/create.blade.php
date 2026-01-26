@extends('layouts.user')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('internal.dashboard') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width: 16px; margin-right: 8px;"></i> Retour au tableau de bord
    </a>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        Signaler un Incident Technique
    </div>
    <div class="card-body">
        
        @if(session('success'))
            <div style="background-color: #dcfce7; border: 1px solid #22c55e; color: #15803d; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('internal.incidents.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Ressource concernée</label>
                <select name="resource_id" class="form-control" required>
                    <option value="">Sélectionnez une ressource</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}">{{ $resource->name }} ({{ $resource->category->name ?? 'Autre' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label class="form-label">Titre de l'incident</label>
                <input type="text" name="title" class="form-control" placeholder="Ex: Serveur inaccessible, Panne de courant..." required>
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label class="form-label">Description détaillée</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Décrivez le problème en détail..." required></textarea>
            </div>

            <div style="margin-top: 24px; text-align: right;">
                <button type="submit" class="btn btn-danger">Signaler l'incident</button>
            </div>
        </form>
    </div>
</div>
@endsection
