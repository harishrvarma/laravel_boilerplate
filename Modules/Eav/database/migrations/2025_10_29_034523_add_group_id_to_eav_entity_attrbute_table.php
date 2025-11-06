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
            if (!Schema::hasColumn('table_eav_entity_attribute', 'group_id')) {
                $table->unsignedBigInteger('group_id')->nullable()->after('entity_type_id');
            }

            $table->foreign('group_id', 'fk_attr_group')
                  ->references('group_id')
                  ->on('table_eav_attribute_group')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_eav_entity_attribute', function (Blueprint $table) {
            if (Schema::hasColumn('table_eav_entity_attribute', 'group_id')) {
                $table->dropForeign('fk_attr_group');
                $table->dropColumn('group_id');
            }
        });
    }
};
