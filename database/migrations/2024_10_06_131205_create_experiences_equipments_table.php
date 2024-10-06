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
        if (Schema::hasTable('experiences_equipments')) {
            return;
        }
        Schema::create('experiences_equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences_equipments');
    }
};
