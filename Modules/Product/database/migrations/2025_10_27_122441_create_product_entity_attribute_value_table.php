<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductEntityAttributeValueTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_entity_attribute_value', function (Blueprint $table) {
            $table->bigIncrements('value_id');
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('attribute_id');
            $table->integer('lang_id')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('entity_id')
                ->references('entity_id')
                ->on('product_entity')
                ->onDelete('cascade');

            $table->foreign('attribute_id')
                ->references('attribute_id')
                ->on('table_eav_entity_attribute')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_entity_attribute_value');
    }
}
