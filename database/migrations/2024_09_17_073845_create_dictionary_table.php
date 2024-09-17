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
        if (Schema::hasTable('dictionary')) {
            return;
        }
        Schema::create('dictionary', function (Blueprint $table) {
            $table->id();
            $table->string('en')->nullable();
            $table->string('uk')->nullable();
            $table->string('ru')->nullable();
            $table->text('description')->nullable();
            $table->text('example')->nullable();
            $table->bigInteger('author')->nullable();
            $table->bigInteger('editor')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('dictionary')) {
            return;
        }
        Schema::dropIfExists('dictionary');
    }
};
