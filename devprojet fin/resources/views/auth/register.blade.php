@extends('layouts/auth')

@section('content')
    <h2>Créer un compte</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label>Nom complet</label>
            <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Adresse Email</label>
            <input type="email" name="email" placeholder="exemple@email.com" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Rôle souhaité</label>
            <select name="role" required>
                <option value="">-- Choisir un rôle --</option>
                <option value="student">Étudiant</option>
                <option value="teacher">Enseignant</option>
            </select>
        </div>

        <div class="form-group">
            <label>Justification</label>
            <textarea name="justification" rows="3" placeholder="Pourquoi souhaitez-vous créer un compte ?" required>{{ old('justification') }}</textarea>
        </div>

        <button type="submit" class="btn">Envoyer la demande</button>
    </form>

    <div class="auth-links">
        Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
    </div>
@endsection
