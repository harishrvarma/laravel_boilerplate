<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('event_listerner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_id');
            $table->string('name', 150);
            $table->string('component', 150);
            $table->string('method', 100)->default('handle');
            $table->integer('order_no')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('event')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('event_listerner');
    }
};