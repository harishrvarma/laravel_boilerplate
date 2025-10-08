<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('config_keys', function (Blueprint $table) {
            if (!Schema::hasColumn('config_keys', 'options_source')) {
                $table->string('options_source')->nullable()->after('default_value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('config_keys', function (Blueprint $table) {
            if (Schema::hasColumn('config_keys', 'options_source')) {
                $table->dropColumn('options_source');
            }
        });
    }
};
