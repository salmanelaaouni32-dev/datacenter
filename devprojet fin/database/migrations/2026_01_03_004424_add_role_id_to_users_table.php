<?php

// database/migrations/YYYY_MM_DD_..._add_role_id_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // AJOUT DU CHAMP role_id
            $table->foreignId('role_id')->constrained()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Suppression de la contrainte et de la colonne en cas de rollback
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};