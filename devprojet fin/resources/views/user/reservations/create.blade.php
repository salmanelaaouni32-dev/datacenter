@extends('layouts.user')

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('internal.catalogue') }}" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width: 16px; margin-right: 8px;"></i> Retour au catalogue
    </a>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        Demande de Réservation
    </div>
    <div class="card-body">
        
        <div style="display: flex; gap: 20px; margin-bottom: 24px; padding: 16px; background-color: #f8fafc; border-radius: 8px;">
             <div style="display: flex; align-items: center; justify-content: center; width: 64px; height: 64px; background-color: var(--white); border-radius: 8px; border: 1px solid var(--border-color);">
                @if(Str::contains(strtolower($resource->category->name ?? ''), 'serveur'))
                    <i data-lucide="server" style="width: 32px; height: 32px; color: var(--secondary-color);"></i>
                @elseif(Str::contains(strtolower($resource->category->name ?? ''), 'stockage'))
                    <i data-lucide="hard-drive" style="width: 32px; height: 32px; color: var(--secondary-color);"></i>
                @else
                    <i data-lucide="box" style="width: 32px; height: 32px; color: var(--secondary-color);"></i>
                @endif
             </div>
             <div>
                 <h3 style="margin: 0 0 4px 0; font-size: 1.1rem;">{{ $resource->name }}</h3>
                 <div style="font-size: 0.9rem; color: var(--text-muted);">{{ $resource->category->name }}</div>
                 <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">{{ $resource->location }}</div>
             </div>
        </div>

        @if(session('error'))
            <div style="background-color: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="resource_id" value="{{ $resource->id }}">

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Date et Heure de Début</label>
                    <input type="datetime-local" name="start_time" class="form-control" required value="{{ old('start_time') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Date et Heure de Fin</label>
                    <input type="datetime-local" name="end_time" class="form-control" required value="{{ old('end_time') }}">
                </div>
            </div>

            <div class="form-group" style="margin-top: 16px;">
                <label class="form-label">Justification</label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="Justifiez votre demande...">{{ old('reason') }}</textarea>
            </div>

            <div style="margin-top: 24px; text-align: right;">
                <button type="submit" class="btn btn-primary">Envoyer la demande</button>
            </div>
        </form>
    </div>
</div>
@endsection
