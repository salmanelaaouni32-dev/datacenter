<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentPath = $request->path();

            $excludedPaths = [
                'password/change',
                'logout',
                'login',
            ];

            if (($user->must_change_password ?? false) === true && !in_array($currentPath, $excludedPaths)) {
                return redirect()->route('password.change.form')->with('warning', 'Veuillez changer votre mot de passe avant de continuer.');
            }
        }

        return $next($request);
    }
}

