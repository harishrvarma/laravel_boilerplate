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
        Schema::create('table_eav_attribute_group', function (Blueprint $table) {
            $table->bigIncrements('group_id');
            $table->unsignedBigInteger('entity_type_id');
            $table->string('code', 100);
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->foreign('entity_type_id')
                  ->references('entity_type_id')
                  ->on('table_eav_entity_type')
                  ->cascadeOnDelete();

            $table->unique(['entity_type_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_eav_attribute_group');
    }
};
