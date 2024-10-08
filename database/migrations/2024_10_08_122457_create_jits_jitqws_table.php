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
        if (Schema::hasTable('jits_jitqws')) {
            return;
        }
        Schema::create('jits_jitqws', function (Blueprint $table) {
            $table->id();            
            $table->foreignId('jit_id')->constrained('jits')->onDelete('cascade');
            $table->foreignId('jitqw_id')->constrained('jitqws')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('jits_jitqws')) {
            return;
        }
        Schema::dropIfExists('jits_jitqws');
    }
};
