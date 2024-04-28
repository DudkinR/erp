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
        if (Schema::hasTable('dimensions')) {
            return;
        }
        Schema::create('dimensions', function (Blueprint $table) {
            $table->id();
            $table->string('abv')->nullable();
            $table->string('name');
            //`kod` VARCHAR(16) NOT NULL AFTER `name`;
            $table->string('kod' , 16 )->nullable();
            $table->text('description')->nullable();
            $table->string('formula')->nullable();
            $table->string('unit')->nullable();
            $table->string('type')->nullable();
            $table->integer('value')->nullable();
            $table->integer('min_value')->nullable();
            $table->integer('max_value')->nullable();
            $table->integer('step')->nullable();
            $table->integer('default_value')->nullable();
            $table->integer('default_min_value')->nullable();
            $table->integer('default_max_value')->nullable();
            $table->integer('default_step')->nullable();
            $table->string('default_type')->nullable();
            $table->string('default_unit')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('dimensions')) {
            return;
        }
        Schema::dropIfExists('dimensions');
    }
};
