<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountRequest; // Assurez-vous d'avoir ce modèle

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
    public function showForm()
    {
        return view('auth.register');
    }

    /**
     * Traite la demande d'inscription et crée un AccountRequest.
     * * NOTE: La méthode a été renommée 'store' pour correspondre à la route POST /register.
     */
    public function store(Request $request) 
    {
        // Validation des champs
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:account_requests,email', // Vérifie l'unicité dans la table account_requests
            'role' => 'required|string',
            'justification' => 'required|string|min:10',
        ]);

        // Création de la demande de compte
        AccountRequest::create($request->all());

        // Redirection vers le formulaire avec un message de succès
        return redirect()->route('register')->with('success', 'Votre demande a été envoyée.');
    }
}