<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés massivement (Mass assignable).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Clé étrangère ajoutée
        'status',
        'must_change_password',
    ];

    /**
     * Les attributs qui doivent être masqués pour la sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'must_change_password' => 'boolean',
    ];

    // --- Relations ---

    /**
     * Un utilisateur appartient à un Rôle.
     * Relation One-to-One inverse (BelongsTo).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * L'utilisateur a fait plusieurs Réservations.
     * Relation One-to-Many (HasMany).
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * L'utilisateur peut signaler plusieurs Incidents.
     * Relation One-to-Many (HasMany).
     */
    public function incidentsReported(): HasMany
    {
        // Supposant que la table 'incidents' a une colonne 'user_id'
        return $this->hasMany(Incident::class);
    }
    
    /**
     * L'utilisateur (s'il est Responsable Technique) gère plusieurs Ressources.
     * Relation One-to-Many (HasMany).
     */
    public function resourcesManaged(): HasMany
    {
        // La clé étrangère dans la table 'resources' est 'manager_id'
        return $this->hasMany(Resource::class, 'manager_id');
    }

    /**
     * L'utilisateur est lié à plusieurs Logs (actions qu'il a effectuées).
     * Relation One-to-Many (HasMany).
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    // --- Fonctions d'aide (Helpers) ---

    /**
     * Vérifie si l'utilisateur a un rôle spécifique.
     * Utilisé principalement par le RoleMiddleware.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

}
