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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('title', 150);
            $table->string('icon', 50)->nullable();
            $table->enum('area', ['admin','api','frontend'])->default('admin');
            $table->enum('item_type', ['folder','file'])->default('file');
            $table->unsignedBigInteger('resource_id')->nullable()->index();
            $table->string('path_ids', 255)->default('');
            $table->integer('level')->default(0);
            $table->integer('order_no')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        
            // Menu hierarchy cascade
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');
        
            // Resource cascade
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
        Schema::dropIfExists('menus');
    }
};
