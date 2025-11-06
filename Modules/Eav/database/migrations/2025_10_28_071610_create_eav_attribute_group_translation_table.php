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
        Schema::create('table_eav_attribute_group_translation', function (Blueprint $table) {
            $table->bigIncrements('translation_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedInteger('lang_id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('group_id')
                  ->references('group_id')
                  ->on('table_eav_attribute_group')
                  ->cascadeOnDelete();

            $table->unique(['group_id', 'lang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_eav_attribute_group_translation');
    }
};
