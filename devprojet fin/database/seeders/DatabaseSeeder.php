<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // <<--- Import essentiel pour la désactivation des clés étrangères

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Cette méthode est l'orchestrateur de tous les Seeders.
     */
    public function run(): void
    {
        // 1. DÉSACTIVATION DES CONTRAINTES DE CLÉS ÉTRANGÈRES
        // Ceci permet de vider les tables (via truncate) dans n'importe quel ordre,
        // même si elles ont des relations entre elles.
        Schema::disableForeignKeyConstraints();

        // 2. APPEL DES SEEDERS DANS L'ORDRE LOGIQUE DE DÉPENDANCE
        // RoleSeeder doit être appelé en premier car UserSeeder en a besoin.
        $this->call([
            RoleSeeder::class, 
            CategorySeeder::class, // <-- Ajoutez cette ligne ici
            UserSeeder::class,
            ResourceSeeder::class, // <-- Ajoutez cette ligne ici
            // (Ajoutez ici ResourceSeeder::class quand il sera créé)
        ]);

        // 3. RÉACTIVATION DES CONTRAINTES DE CLÉS ÉTRANGÈRES
        Schema::enableForeignKeyConstraints();
    }
}