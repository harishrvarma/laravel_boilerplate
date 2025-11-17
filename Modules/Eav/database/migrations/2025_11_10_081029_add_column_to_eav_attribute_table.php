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
        Schema::table('table_eav_entity_attribute', function (Blueprint $table) {
            $table->boolean('lang_type')
                  ->default(false)
                  ->after('default_value')
                  ->comment('Whether this attribute is language-specific');
        });
    }

    public function down(): void
    {
        Schema::table('table_eav_entity_attribute', function (Blueprint $table) {
            $table->dropColumn('lang_type');
        });
    }
};
