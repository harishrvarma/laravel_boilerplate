<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_eav_entity_attribute_value', function (Blueprint $table) {
            $table->bigIncrements('value_id');
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedInteger('lang_id')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('entity_id')
                  ->references('entity_id')
                  ->on('table_eav_entity')
                  ->cascadeOnDelete();

            $table->foreign('attribute_id')
                  ->references('attribute_id')
                  ->on('table_eav_entity_attribute')
                  ->cascadeOnDelete();

            $table->unique(['entity_id', 'attribute_id', 'lang_id'], 'eav_val_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_eav_entity_attribute_value');
    }
}
