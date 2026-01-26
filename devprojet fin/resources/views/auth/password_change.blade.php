@extends('layouts/auth')

@section('content')
<h2>Changer mon mot de passe</h2>

@if (session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
@endif

<form method="POST" action="{{ route('password.change.update') }}">
    @csrf
    <div>
        <label>Mot de passe actuel</label>
        <input type="password" name="current_password" required>
        @error('current_password') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label>Nouveau mot de passe</label>
        <input type="password" name="password" required>
        @error('password') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label>Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" required>
    </div>
    <button type="submit">Enregistrer</button>
</form>
@endsection

