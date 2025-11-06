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
        Schema::create('table_entity_attribute_config', function (Blueprint $table) {
            $table->bigIncrements('config_id');

            $table->unsignedBigInteger('entity_type_id');
            $table->unsignedBigInteger('attribute_id');

            $table->boolean('show_in_grid')->default(true)->comment('Show this attribute in grid');
            $table->boolean('default_in_grid')->default(false)->comment('default Show this attribute in grid');
            $table->boolean('is_sortable')->default(false)->comment('Can this column be sorted');
            $table->boolean('is_filterable')->default(false)->comment('Can this column be filtered');
            $table->timestamps();
            $table->unique(['entity_type_id', 'attribute_id'], 'uniq_entity_attribute');
            $table->foreign('entity_type_id')->references('entity_type_id')->on('table_eav_entity_type')->onDelete('cascade');
            $table->foreign('attribute_id')->references('attribute_id')->on('table_eav_entity_attribute')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_entity_attribute_config');
    }
};
