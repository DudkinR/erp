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
        if (Schema::hasTable('step_control')) {
            return;
        }
        Schema::create('step_control', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained();
            $table->foreignId('control_id')->constrained();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('step_control')) {
            return;
        }
        Schema::dropIfExists('step_control');
    }
};
