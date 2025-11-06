<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavAttributesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_eav_entity_attribute', function (Blueprint $table) {
            $table->bigIncrements('attribute_id');
            $table->unsignedBigInteger('entity_type_id');
            $table->string('code', 255);
            $table->string('data_type', 50)->nullable();
            $table->string('data_model', 100)->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('position')->default(0);
            $table->text('default_value')->nullable();
            $table->timestamps();

            $table->foreign('entity_type_id')
                  ->references('entity_type_id')
                  ->on('table_eav_entity_types')
                  ->cascadeOnDelete();
                  
            $table->unique(['entity_type_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_eav_entity_attribute');
    }
}
