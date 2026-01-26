<?php

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
        Schema::table('resources', function (Blueprint $table) {
            $table->integer('cpu_cores')->nullable();
            $table->integer('ram_gb')->nullable();
            $table->integer('storage_tb')->nullable(); // Ou gb selon besoin
            $table->string('os_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['cpu_cores', 'ram_gb', 'storage_tb', 'os_name']);
        });
    }
};
