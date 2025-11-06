<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEavAttributeOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_eav_entity_attribute_option_translation', function (Blueprint $table) {
            $table->bigIncrements('translation_id');
            $table->unsignedBigInteger('option_id');
            $table->unsignedInteger('lang_id');
            $table->string('display_name', 255);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('option_id')
                  ->references('option_id')
                  ->on('table_eav_entity_attribute_option')
                  ->cascadeOnDelete();

            $table->unique(['option_id', 'lang_id'], 'eav_opt_lang_uq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_eav_entity_attribute_option_translation');
    }
}
