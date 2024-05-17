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
        Schema::create('struct_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
                ->constrained('tasks');
            $table->foreignId('struct_id')
                ->constrained('structuries');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structure_task');
    }
};
