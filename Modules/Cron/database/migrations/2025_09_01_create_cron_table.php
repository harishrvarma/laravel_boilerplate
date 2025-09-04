<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cron_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   
            $table->string('command');                
            $table->string('expression');             
            $table->boolean('is_active')->default(1); 
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cron_schedules');
    }
};