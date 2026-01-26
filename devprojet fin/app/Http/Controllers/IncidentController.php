<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    public function create()
    {
        // Return a view to report an incident
        // We need to fetch resources or let user select one
        $resources = \App\Models\Resource::all();
        return view('user.incidents.create', compact('resources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:1000',
        ]);

        $incident = Incident::create([
            'user_id' => Auth::id(),
            'resource_id' => $validated['resource_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'pending', // Statut initial
        ]);
        
        // Notification au Responsable Technique (ou à l'Admin) de la ressource concernée

        return back()->with('success', 'Incident signalé avec succès. Un responsable sera notifié.');
    }
}