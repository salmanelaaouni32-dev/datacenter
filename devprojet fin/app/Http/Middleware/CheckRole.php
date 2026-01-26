<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Mapping des slugs de route vers les noms de rôle en base de données
        $roleMap = [
            'admin' => 'Administrateur',
            'responsable' => 'Responsable Technique',
            'utilisateur' => 'Utilisateur Interne',
        ];

        $expectedRoleName = $roleMap[$role] ?? $role;

        // Vérifie le rôle (supposant que la relation 'role' est chargée ou accessible)
        // On utilise safe null operator au cas où la relation est vide
        if ($user->role?->name !== $expectedRoleName) {
             abort(403, 'Accès non autorisé. Rôle requis : ' . $role);
        }
        


        return $next($request);
    }
}
