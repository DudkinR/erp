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
        Schema::create('task_task', function (Blueprint $table) {
            $table->id();
            // parent task
            $table->foreignId('parent_task_id')->nullable()
            ->default(null)
            ->constrained('task_task')->onDelete('cascade');
            // childre task
            $table->foreignId('child_task_id')->nullable()->constrained('task_task')->onDelete('cascade');
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_task');
    }
};
