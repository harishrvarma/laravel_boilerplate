<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /**
         * 1. config_keys
         */
        Schema::create('config_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key_name')->unique();
            $table->string('label');
            $table->enum('input_type', [
                'text','number','select','radio','checkbox',
                'textarea','boolean','file','date'
            ]);
            $table->boolean('is_required')->default(false);
            $table->string('default_value')->nullable();
            $table->string('validation_rule')->nullable();
            $table->boolean('is_user_config')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        /**
         * 2. config_options
         */
        Schema::create('config_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_key_id')
                  ->constrained('config_keys')
                  ->onDelete('cascade');
            $table->string('option_label');
            $table->string('option_value');
            $table->integer('position')->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        /**
         * 3. config_values
         */
        Schema::create('config_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_key_id')
                  ->constrained('config_keys')
                  ->onDelete('cascade');
            $table->foreignId('admin_id')
                  ->nullable()
                  ->constrained('admin')
                  ->onDelete('cascade');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        /**
         * 4. config_groups (optional)
         */
        Schema::create('config_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        /**
         * 5. config_key_group (optional, many-to-many)
         */
        Schema::create('config_key_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_key_id')
                  ->constrained('config_keys')
                  ->onDelete('cascade');
            $table->foreignId('group_id')
                  ->constrained('config_groups')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('config_key_group');
        Schema::dropIfExists('config_groups');
        Schema::dropIfExists('config_values');
        Schema::dropIfExists('config_options');
        Schema::dropIfExists('config_keys');
    }
};
