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
        if (Schema::hasTable('cars')) {
            return;
        }
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');// marka
            $table->foreignId('type_id')->constrained('types')->onDelete('cascade');// model
            // gov number
            $table->string('gov_number')->unique();
            // condition id
            $table->foreignId('condition_id')->constrained('types')->onDelete('cascade');
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('cars')) {
            return;
        }
        Schema::dropIfExists('cars');
    }
};
