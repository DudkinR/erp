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
        if (Schema::hasTable('project_stage')) {
            return;
        }
        Schema::create('project_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('stage_id')->constrained();
            $table->integer('performance')->nullable();
            // control_date
            $table->date('control_date')->nullable();
            // control_result
            $table->string('control_result')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('project_stage')) {
            return;
        }
        Schema::dropIfExists('project_stage');
    }
};
