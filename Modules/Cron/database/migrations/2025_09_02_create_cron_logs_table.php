<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cron_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cron_id')->constrained('cron')->onDelete('cascade');
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('message')->nullable();      // Log message or error
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cron_log');
    }
};
