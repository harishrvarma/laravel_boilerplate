<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cron_schedules', function (Blueprint $table) {
            $table->bigIncrements('schedule_id');
            $table->unsignedBigInteger('cron_id');
            $table->dateTime('scheduled_for')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Pending,1=Success,2=Failure');
            $table->longText('log')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->foreign('cron_id')->references('cron_id')->on('crons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cron_schedules');
    }
};
