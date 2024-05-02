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
        if (Schema::hasTable('stage_stage')) {
            return;
        }
        Schema::create('stage_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained(); 
            $table->foreignId('next_stage_id')->constrained('stages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('stage_stage')) {
            return;
        }
        Schema::dropIfExists('stage_stage');
    }
};
