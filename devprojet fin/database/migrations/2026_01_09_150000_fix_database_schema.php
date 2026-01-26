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
        // Disable constraints to allow dropping tables in any order
        Schema::disableForeignKeyConstraints();

        // Drop tables if they exist to ensure clean state
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('categories');

        // Re-enable constraints for creation
        Schema::enableForeignKeyConstraints();

        // 1. Categories (No Dependencies)
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 2. Notifications (Standard Laravel Schema)
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                // $table->uuid('id')->primary(); // Standard Laravel uses UUID
                // But if your User model uses Notifiable trait, it expects UUIDs by default.
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // 3. Reservations (Depends on Users, Resources)
        // Note: resources table should already exist from previous migration 2026_01_03...
        // If resource_id constraint fails, it means resources table is missing or incompatible.
        // We will make resource_id nullable/loose to prevent crash.
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('resource_id')->nullable(); // Loose constraint
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        // 4. Incidents (Depends on Users, Resources)
        if (!Schema::hasTable('incidents')) {
             Schema::create('incidents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('resource_id')->nullable(); // Loose constraint
                $table->string('title');
                $table->text('description');
                $table->string('status')->default('open');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('categories');
        Schema::enableForeignKeyConstraints();
    }
};
