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
        if (Schema::hasTable('dimension_task')) {
            return;
        }
        Schema::create('dimension_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimension_id')->constrained();
            $table->foreignId('task_id')->constrained();
            $table->string('value')->nullable();
            $table->string('fact')->nullable();
            $table->string('status')->nullable();
            $table->text('comment')->nullable();
            $table->integer('personal_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('dimension_task')) {
            return;
        }
        Schema::dropIfExists('dimension_task');
    }
};
