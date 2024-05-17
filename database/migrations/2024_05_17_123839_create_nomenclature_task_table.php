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
        if (Schema::hasTable('nomenclature_task')) {
            return;
        }
        Schema::create('nomenclature_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')
            ->constrained('tasks');
            $table->foreignId('nomenclature_id')
            ->constrained('nomenclature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('nomenclature_task')) {
            return;
        }
        Schema::dropIfExists('nomenclature_task');
    }
};
