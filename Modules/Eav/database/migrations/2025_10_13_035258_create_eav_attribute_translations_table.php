<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavAttributeTranslationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('table_eav_entity_attribute_translation', function (Blueprint $table) {
            $table->bigIncrements('translation_id');
            $table->unsignedBigInteger('attribute_id');
            $table->unsignedInteger('lang_id');
            $table->string('display_name', 255);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('attribute_id')
                  ->references('attribute_id')
                  ->on('table_eav_entity_attribute')
                  ->cascadeOnDelete();

            $table->unique(['attribute_id', 'lang_id'], 'eav_attr_lang_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_eav_entity_attribute_translation');
    }
}
