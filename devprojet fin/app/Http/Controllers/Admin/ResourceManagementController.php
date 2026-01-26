<?php

// app/Http/Controllers/Admin/ResourceManagementController.php

use App\Models\Resource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceManagementController extends Controller
{
    // ... index, create (méthodes vues précédemment) ...

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'manager_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            
            // Validation des spécifications techniques de la table 'resources'
            'cpu_cores' => 'nullable|integer|min:1',
            'ram_gb' => 'nullable|integer|min:1',
            'storage_tb' => 'nullable|numeric|min:0.1',
            'os_name' => 'nullable|string|max:255',
        ]);

        $resource = Resource::create($validatedData);
        
        // Log : Journalisation de la création de la ressource
        Log::create([
            'user_id' => Auth::id(), 
            'action' => 'Resource created', 
            'details' => "Resource {$resource->name} added.",
        ]);

        return redirect()->route('admin.resource-management.index')->with('success', 'Ressource créée avec succès.');
    }
    
    // ... edit, update, destroy (méthodes CRUD standards)
}