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
            $table->string('description')->nullable();
            $table->string('formula')->nullable();
            $table->string('unit')->nullable();
            $table->string('type')->nullable();
            $table->string('value')->nullable();
            $table->string('min_value')->nullable();
            $table->string('max_value')->nullable();
            $table->string('step')->nullable();
            $table->string('default_value')->nullable();
            $table->string('default_min_value')->nullable();
            $table->string('default_max_value')->nullable();
            $table->string('default_step')->nullable();
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
