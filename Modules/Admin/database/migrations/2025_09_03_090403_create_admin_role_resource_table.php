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
        Schema::create('admin_role_resource', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('resource_id');
            $table->timestamps();

            $table->unique(['role_id', 'resource_id']);

            $table->foreign('role_id')
                  ->references('id')
                  ->on('admin_role')
                  ->onDelete('cascade');

            $table->foreign('resource_id')
                  ->references('id')
                  ->on('admin_resource')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_role_resource');
    }
};
