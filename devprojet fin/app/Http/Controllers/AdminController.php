<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // ✅ Import correct
use App\Models\AccountRequest;
use App\Models\Category;
use App\Models\Incident;
use App\Models\Maintenance;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\Role;
use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    /**
     * Dashboard - statistiques globales
     */
    public function dashboard()
    {
        // Statistiques globales
        $stats = [
            'totalResources' => Resource::count(),
            'totalUsers' => User::count(),
            'pendingReservations' => Reservation::where('status', 'pending')->count(),
            'activeReservations' => Reservation::where('status', 'active')->count(),
            'usersByRole' => User::join('roles', 'users.role_id', '=', 'roles.id')
                                 ->select('roles.name', DB::raw('count(*) as count'))
                                 ->groupBy('roles.name')
                                 ->pluck('count', 'roles.name'),
            'pendingIncidents' => Incident::where('status', '!=', 'resolved')->count(),
            'totalCategories' => Category::count(),
        ];

        $pendingRequests = AccountRequest::where('status', 'pending')->get();
        $pendingReservationsList = Reservation::with(['user', 'resource'])
                                            ->where('status', 'pending')
                                            ->oldest()
                                            ->get();
        $recentReservations = Reservation::with(['user', 'resource'])
                                        ->latest()
                                        ->take(5)
                                        ->get();

        return view('admin.dashboard', compact('stats', 'pendingRequests', 'pendingReservationsList', 'recentReservations'));
    }

    /**
     * Approve account request
     */
    public function approveRequest($id)
    {
        $request = AccountRequest::findOrFail($id);

        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'Un utilisateur avec cet email existe déjà.');
        }

        $requestedRole = $request->role;
        $roleMap = [
            'Student' => 'Utilisateur Interne',
            'Étudiant' => 'Utilisateur Interne',
            'Admin' => 'Administrateur',
            'Manager' => 'Responsable Technique',
        ];
        $normalizedRole = $roleMap[$requestedRole] ?? $requestedRole;
        $role = Role::where('name', $normalizedRole)->first();
        if (!$role) {
            $role = Role::where('name', 'Utilisateur Interne')->first();
        }

        $mailStatus = null;
        DB::transaction(function () use ($request, $role, &$mailStatus) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password123'), // mot de passe par défaut
                'role_id' => $role->id,
                'status' => 'active',
                'must_change_password' => true,
            ]);

            $request->update(['status' => 'approved']);

            try {
                Mail::raw(
                    "Bonjour {$request->name},\n\nVotre compte a été approuvé.\nEmail: {$request->email}\nMot de passe initial: password123\n\nPour des raisons de sécurité, veuillez vous connecter puis changer votre mot de passe immédiatement: " . route('password.change.form') . "\n\nCordialement.",
                    function ($message) use ($request) {
                        $message->to($request->email)
                            ->subject('Votre compte a été approuvé');
                    }
                );
                $mailStatus = 'sent';
            } catch (\Throwable $e) {
                // En cas d'échec d'envoi email (config manquante), on ignore et on journalise
                Log::create([
                    'user_id' => auth()->id(),
                    'action' => 'mail_send_failed',
                    'target' => 'Account approval ' . $request->email,
                    'details' => $e->getMessage(),
                    'ip_address' => request()->ip(),
                ]);
                $mailStatus = 'failed';
            }
        });

        return back()->with('success', "Compte approuvé pour {$request->name}.")
            ->with('mail_info', $mailStatus === 'sent' ? 'Email de notification envoyé.' : 'Email de notification non envoyé. Voir les logs.');
    }

    /**
     * Reject account request
     */
    public function rejectRequest($id)
    {
        $request = AccountRequest::findOrFail($id);
        $request->update(['status' => 'refused']);

        $mailStatus = null;
        try {
            Mail::raw(
                "Bonjour {$request->name},\n\nVotre demande de création de compte a été refusée.\n\nCordialement.",
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('Votre demande de compte a été refusée');
                }
            );
            $mailStatus = 'sent';
        } catch (\Throwable $e) {
            Log::create([
                'user_id' => auth()->id(),
                'action' => 'mail_send_failed',
                'target' => 'Account refused ' . $request->email,
                'details' => $e->getMessage(),
                'ip_address' => request()->ip(),
            ]);
            $mailStatus = 'failed';
        }

        return back()->with('success', 'Demande refusée.')
            ->with('mail_info', $mailStatus === 'sent' ? 'Email de notification envoyé.' : 'Email de notification non envoyé. Voir les logs.');
    }

    /**
     * Users management
     */
    public function users()
    {
        $users = User::with('role')->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'user_status_toggled',
            'target' => 'User ID ' . $user->id,
            'details' => 'New status: ' . $user->status,
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', "Statut de l'utilisateur mis à jour.");
    }

    public function updateUserRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->role_id = $validated['role_id'];
        $user->save();

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'user_role_updated',
            'target' => 'User ID ' . $user->id,
            'details' => 'New role ID: ' . $validated['role_id'],
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', "Rôle de l'utilisateur mis à jour.");
    }

    /**
     * Resources management
     */
    public function resources()
    {
        $resources = Resource::with(['category', 'manager'])->paginate(10);
        return view('admin.resources.index', compact('resources'));
    }

    public function createResource()
    {
        $categories = Category::all();
        $managers = User::whereHas('role', fn($q) => $q->where('name', 'Responsable Technique'))->get();

        return view('admin.resources.create', compact('categories', 'managers'));
    }

    public function storeResource(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,maintenance,inactive',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'cpu_cores' => 'nullable|integer',
            'ram_gb' => 'nullable|integer',
            'storage_tb' => 'nullable|integer',
            'os_name' => 'nullable|string',
        ]);

        Resource::create($validated);

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'resource_created',
            'target' => 'Resource ' . $validated['name'],
            'details' => json_encode($validated),
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Ressource créée avec succès.');
    }

    public function editResource($id)
    {
        $resource = Resource::findOrFail($id);
        $categories = Category::all();
        $managers = User::whereHas('role', fn($q) => $q->where('name', 'Responsable Technique'))->get();

        return view('admin.resources.edit', compact('resource', 'categories', 'managers'));
    }

    public function updateResource(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,maintenance,inactive',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
            'cpu_cores' => 'nullable|integer',
            'ram_gb' => 'nullable|integer',
            'storage_tb' => 'nullable|integer',
            'os_name' => 'nullable|string',
        ]);

        $resource = Resource::findOrFail($id);
        $resource->update($validated);

        return redirect()->route('admin.resources.index')->with('success', 'Ressource mise à jour.');
    }

    public function destroyResource($id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->reservations()->where('status', 'active')->exists()) {
            return back()->with('error', 'Impossible de supprimer cette ressource : réservations actives.');
        }

        $resource->delete();

        return redirect()->route('admin.resources.index')->with('success', 'Ressource supprimée avec succès.');
    }

    /**
     * Categories management
     */
    public function categories()
    {
        $categories = Category::withCount('resources')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);

        if ($category->resources()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer : cette catégorie contient des ressources.');
        }

        $category->delete();

        return back()->with('success', 'Catégorie supprimée.');
    }

    /**
     * Maintenances
     */
    public function maintenances()
    {
        $maintenances = Maintenance::with('resource')->orderBy('start_time', 'desc')->paginate(10);
        return view('admin.maintenances.index', compact('maintenances'));
    }

    public function createMaintenance()
    {
        $resources = Resource::all();
        return view('admin.maintenances.create', compact('resources'));
    }

    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'reason' => 'required|string',
        ]);

        DB::transaction(function () use ($validated) {
            $maintenance = Maintenance::create([
                'resource_id' => $validated['resource_id'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'reason' => $validated['reason'],
                'status' => 'scheduled',
            ]);

            if (strtotime($validated['start_time']) <= time()) {
                $resource = Resource::find($validated['resource_id']);
                $resource->update(['status' => 'maintenance']);
                $maintenance->update(['status' => 'in_progress']);
            }
        });

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance planifiée.');
    }

    /**
     * Reservations
     */
    public function reservations(Request $request)
    {
        $query = Reservation::with(['user', 'resource']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->paginate(20);

        return view('admin.reservations', compact('reservations'));
    }

    public function approveReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Cette réservation ne peut pas être approuvée.');
        }

        $reservation->update(['status' => 'approved']);
        $reservation->user->notify(new \App\Notifications\ReservationUpdated($reservation, 'approuvée'));

        return back()->with('success', 'Réservation approuvée.');
    }

    public function rejectReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Cette réservation ne peut pas être rejetée.');
        }

        $reservation->update(['status' => 'rejected']);
        $reservation->user->notify(new \App\Notifications\ReservationUpdated($reservation, 'refusée'));

        return back()->with('success', 'Réservation rejetée.');
    }

    /**
     * Delete user safely
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        if ($user->resourcesManaged()->count() > 0) {
            return back()->with('error', 'Utilisateur gère des ressources. Réassigner avant suppression.');
        }

        if ($user->reservations()->where('status', 'active')->exists()) {
            return back()->with('error', 'Utilisateur a des réservations actives.');
        }

        $user->delete();

        Log::create([
            'user_id' => Auth::id(),
            'action' => 'user_deleted',
            'target' => 'User ID ' . $id,
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Utilisateur supprimé.');
    }
}
