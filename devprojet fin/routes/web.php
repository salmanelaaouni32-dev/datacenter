<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\InternalUserController;

// 1. PAGE D'ACCUEIL (Accessible à tous)
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. AUTHENTIFICATION (Utilisateurs non connectés - Middleware 'guest')
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// 3. LOGIQUE DE DÉCONNEXION (Accessible uniquement aux utilisateurs connectés)
// J'ai déplacé cette route à l'intérieur du groupe 'auth' pour une meilleure organisation,
// mais elle était déjà fonctionnelle.
Route::middleware('auth')->group(function () {
    
    // ROUTE DE DÉCONNEXION AJOUTÉE ET SIMPLIFIÉE
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 

    // Route générique /dashboard pour rediriger selon le rôle
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $controller = new LoginController();
        // On utilise la méthode de redirection publique si possible, ou on duplique la logique sinon
        // Pour faire simple ici on redirige manuellement
        if ($user->role->name === 'Administrateur') return redirect()->route('admin.dashboard');
        if ($user->role->name === 'Responsable Technique') return redirect()->route('manager.dashboard');
        if ($user->role->name === 'Utilisateur Interne') return redirect()->route('internal.dashboard');
        return redirect('/')->with('error', 'Rôle non reconnu');
    })->name('dashboard');
    
    // ESPACES PROTÉGÉS (Redirection par rôle après login)
    // Admin
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin', 'force.password.change'], 'as' => 'admin.'], function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::patch('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
        Route::patch('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.update-role');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        
        // Gestion des Ressources
        Route::get('/resources', [AdminController::class, 'resources'])->name('resources.index');
        Route::get('/resources/create', [AdminController::class, 'createResource'])->name('resources.create');
        Route::post('/resources', [AdminController::class, 'storeResource'])->name('resources.store');
        Route::get('/resources/{id}/edit', [AdminController::class, 'editResource'])->name('resources.edit');
        Route::put('/resources/{id}', [AdminController::class, 'updateResource'])->name('resources.update');
        Route::delete('/resources/{id}', [AdminController::class, 'destroyResource'])->name('resources.destroy');

        // Gestion des Catégories
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
        Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/maintenances', [AdminController::class, 'maintenances'])->name('maintenances.index');
        Route::get('/maintenances/create', [AdminController::class, 'createMaintenance'])->name('maintenances.create');
        Route::post('/maintenances', [AdminController::class, 'storeMaintenance'])->name('maintenances.store');
        
        // Gestion des demandes de compte
        Route::post('/account-requests/{id}/approve', [AdminController::class, 'approveRequest'])->name('account-requests.approve');
        Route::post('/account-requests/{id}/reject', [AdminController::class, 'rejectRequest'])->name('account-requests.reject');

        // Gestion des Réservations
        Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations.index');
        Route::patch('/reservations/{id}/approve', [AdminController::class, 'approveReservation'])->name('reservations.approve');
        Route::patch('/reservations/{id}/reject', [AdminController::class, 'rejectReservation'])->name('reservations.reject');
    });
    
    // Manager
    Route::group(['prefix' => 'manager', 'middleware' => ['role:responsable', 'force.password.change'], 'as' => 'manager.'], function () {
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');
        Route::patch('/reservations/{id}/approve', [ManagerController::class, 'approveReservation'])->name('reservations.approve');
        Route::patch('/reservations/{id}/reject', [ManagerController::class, 'rejectReservation'])->name('reservations.reject');
        Route::get('/my-resources', [ManagerController::class, 'myResources'])->name('resources');
        
        // Incidents
        Route::get('/incidents', [ManagerController::class, 'incidents'])->name('incidents');
        Route::patch('/incidents/{id}/resolve', [ManagerController::class, 'resolveIncident'])->name('incidents.resolve');
    });
    
    // Utilisateur
    Route::group(['prefix' => 'user', 'middleware' => ['role:utilisateur', 'force.password.change'], 'as' => 'internal.'], function () {
        Route::get('/dashboard', [InternalUserController::class, 'dashboard'])->name('dashboard');
        Route::get('/catalogue', [InternalUserController::class, 'catalogue'])->name('catalogue');
        Route::get('/reservations', [InternalUserController::class, 'reservations'])->name('reservations');
        
        // Signalement d'incidents
        Route::get('/incidents/create', [\App\Http\Controllers\IncidentController::class, 'create'])->name('incidents.create');
        Route::post('/incidents', [\App\Http\Controllers\IncidentController::class, 'store'])->name('incidents.store');
    });

    // Password change (accessible pour utilisateurs connectés)
    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'showForm'])->name('password.change.form');
    Route::post('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'update'])->name('password.change.update');
    // Reservations
    Route::middleware('auth')->group(function () {
        Route::get('/reservations/create/{resource_id}', [\App\Http\Controllers\ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [\App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
    });

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::patch('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});
