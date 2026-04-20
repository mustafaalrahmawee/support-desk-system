<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_user_id')->constrained('internal_users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->unique(['internal_user_id', 'role_id'], 'internal_user_roles_unique');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_user_roles');
    }
};
