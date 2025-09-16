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
        Schema::create('cache_registry', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->string('type');
            $table->string('key');
            $table->string('store');
            $table->timestamp('last_generated')->nullable();
            $table->string('builder_class')->nullable();
            $table->timestamps();
            $table->unique(['area','type','key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_registry');
    }
};
