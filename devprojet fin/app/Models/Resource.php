<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'category_id', 'manager_id', 'name', 'image', 'location', 'status', 'description',
        'cpu_cores', 'ram_gb', 'storage_tb', 'os_name'
    ];

    // Relation pour récupérer la catégorie
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    // Relation pour récupérer le responsable technique
    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relation pour récupérer toutes les réservations
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
    
    // Relation pour récupérer les spécifications secondaires
    public function specifications() {
        return $this->hasMany(Specification::class);
    }

    public function maintenances() {
        return $this->hasMany(Maintenance::class);
    }

    public function activeReservation() {
        return $this->hasOne(Reservation::class)->where('status', 'active');
    }
}
