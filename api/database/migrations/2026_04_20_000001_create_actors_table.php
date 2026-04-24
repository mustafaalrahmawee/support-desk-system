<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actors', function (Blueprint $table) {
            $table->id();
            $table->string('actor_type'); // 'internal_user' or 'customer'
            $table->foreignId('internal_user_id')
                  ->nullable()
                  ->constrained('internal_users')
                  ->nullOnDelete();
            $table->unsignedBigInteger('customer_id')->nullable(); // FK added when customers table exists
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actors');
    }
};
