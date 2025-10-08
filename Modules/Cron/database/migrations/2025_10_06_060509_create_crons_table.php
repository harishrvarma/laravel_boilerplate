<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('cron_log');
        Schema::dropIfExists('cron');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::create('crons', function (Blueprint $table) {
            $table->bigIncrements('cron_id'); // PK
            $table->string('name');                  
            $table->string('command');               
            $table->string('expression')->nullable();            
            $table->string('class')->nullable();
            $table->string('method')->nullable();
            $table->string('frequency')->default('* * * * *');
            $table->boolean('is_active')->default(1);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crons');
    }
};
