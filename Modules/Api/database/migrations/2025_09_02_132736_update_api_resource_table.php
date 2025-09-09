<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('api_resource', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['description']);

            // Add new columns
            $table->string('label')->after('code');
            $table->string('name')->after('label');
            $table->string('route_name')->after('name');
            $table->string('uri')->nullable()->after('route_name');
            $table->string('method')->after('uri');
            $table->unsignedInteger('level')->after('method');
            $table->unsignedBigInteger('parent_id')->nullable()->after('level');
            $table->string('path_ids')->nullable()->after('parent_id');
            $table->enum('status', ['0', '1'])->default('1')->after('path_ids');
        });
    }
};
