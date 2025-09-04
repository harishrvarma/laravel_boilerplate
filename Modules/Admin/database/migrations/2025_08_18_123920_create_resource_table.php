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
        Schema::create('admin_resource', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('label');
            $table->string('name');
            $table->string('route_name');
            $table->string('uri')->nullable();
            $table->string('method');
            $table->unsignedInteger('level');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('path_ids')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_resource');
    }
};
