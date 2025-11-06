<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_eav_entity', function (Blueprint $table) {
            $table->bigIncrements('entity_id');
            $table->unsignedBigInteger('entity_type_id');
            $table->timestamps();

            $table->foreign('entity_type_id')
                  ->references('entity_type_id')
                  ->on('table_eav_entity_type')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_eav_entity');
    }
}
