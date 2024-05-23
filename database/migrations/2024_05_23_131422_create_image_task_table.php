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
    {   if (Schema::hasTable('image_task')) {
            return;
        }
        Schema::create('image_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained('images');
            $table->foreignId('task_id')->constrained('tasks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('image_task')) {
            return;
        }
        Schema::dropIfExists('image_task');
    }
};
