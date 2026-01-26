<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider la table des rôles (TABLE PRINCIPALE - peut être vidée maintenant)
        DB::table('roles')->truncate(); 

        $now = Carbon::now();

        DB::table('roles')->insert([
            // 1. Administrateur
            [
                'name' => 'Administrateur', 
                'description' => 'Gestion complète du Data Center, du catalogue et des utilisateurs.',
                'created_at' => $now, 
                'updated_at' => $now
            ],
            // 2. Responsable Technique
            [
                'name' => 'Responsable Technique', 
                'description' => 'Supervision des ressources, gestion des maintenances, approbation des réservations.',
                'created_at' => $now, 
                'updated_at' => $now
            ],
            // 3. Utilisateur Interne
            [
                'name' => 'Utilisateur Interne', 
                'description' => 'Consultation du catalogue et soumission des demandes de réservation.',
                'created_at' => $now, 
                'updated_at' => $now
            ],
            // 4. Invité
            [
                'name' => 'Invité', 
                'description' => 'Consultation publique limitée des ressources disponibles.',
                'created_at' => $now, 
                'updated_at' => $now
            ],
        ]);
    }
}