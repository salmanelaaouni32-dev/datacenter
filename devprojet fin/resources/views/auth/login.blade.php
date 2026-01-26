@extends('layouts/auth')

@section('content')
    <h2>Connexion</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label>Adresse Email</label>
            <input type="email" name="email" placeholder="exemple@email.com" required>
        </div>

        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn">Se connecter</button>
    </form>

    <div class="auth-links">
        Nouveau ? <a href="{{ route('register') }}">Créer un compte</a>
    </div>
@endsection