<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->unsignedBigInteger('locale_id')->nullable()->after('module');

            $table->foreign('locale_id')
                ->references('id')
                ->on('translations_locale')
                ->onDelete('cascade');

            if (Schema::hasColumn('translations', 'locale')) {
                $table->dropColumn('locale');
            }
        });
    }

    public function down(): void
    {
        Schema::table('translations', function (Blueprint $table) {
            if (!Schema::hasColumn('translations', 'locale')) {
                $table->string('locale', 10)->after('module');
            }

            if (Schema::hasColumn('translations', 'locale_id')) {
                $table->dropForeign(['locale_id']);
                $table->dropColumn('locale_id');
            }
        });
    }
};

