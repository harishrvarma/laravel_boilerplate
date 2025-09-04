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
        Schema::create('admin_user_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->unique(['admin_id', 'role_id']);

            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admin')
                  ->onDelete('cascade');

            $table->foreign('role_id')
                  ->references('id')
                  ->on('admin_role')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_role');
    }
};
