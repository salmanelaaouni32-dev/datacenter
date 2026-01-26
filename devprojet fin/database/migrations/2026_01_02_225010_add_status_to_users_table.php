<?php


// database/migrations/YYYY_MM_DD_..._add_status_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // AJOUT DU CHAMP STATUS
            // 'active' sera la valeur par défaut pour les nouveaux utilisateurs
            $table->string('status')->default('active')->after('password'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revenir en arrière en supprimant le champ
            $table->dropColumn('status');
        });
    }
};