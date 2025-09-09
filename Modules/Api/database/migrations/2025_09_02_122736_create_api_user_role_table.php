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
        Schema::create('api_user_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // BIGINT UNSIGNED NOT NULL
            $table->unsignedBigInteger('role_id');  // BIGINT UNSIGNED NOT NULL
            $table->timestamps();                   // created_at, updated_at

            // Unique user-role combination
            $table->unique(['user_id', 'role_id']);

            // Foreign keys with cascade delete
            $table->foreign('user_id')
                  ->references('id')
                  ->on('api_user')
                  ->onDelete('cascade');

            $table->foreign('role_id')
                  ->references('id')
                  ->on('api_role')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_user_role');
    }
};
