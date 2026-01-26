<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    protected function redirectByRole($user)
    {
        // 1. Vérification du statut du compte
        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->withErrors('Votre compte est inactif.');
        }

        // 2. Récupération du nom du rôle
        // Assurez-vous que la relation 'role' existe sur votre modèle User
        $roleName = $user->role->name ?? null;

        switch ($roleName) {
            case 'Administrateur':
                return redirect()->route('admin.dashboard');
            case 'Responsable Technique':
                return redirect()->route('manager.dashboard');
            case 'Utilisateur Interne':
                return redirect()->route('internal.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->withErrors("Rôle non reconnu ou manquant.");
        }
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Déconnecte la session utilisateur de Laravel
        $request->session()->invalidate(); // Invalide la session
        $request->session()->regenerateToken(); // Régénère le jeton CSRF pour la sécurité
        
        // Redirige vers la page d'accueil
        return redirect()->route('home'); // Utilisation de route('home') au lieu de '/'
    }
}
