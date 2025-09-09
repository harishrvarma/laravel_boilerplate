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
        Schema::create('api_user', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);                          
            $table->string('email', 150)->unique();               
            $table->string('password', 255);
            $table->tinyInteger('status')->default(1);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_user');
    }
};
