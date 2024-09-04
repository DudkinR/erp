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
        if (!Schema::hasTable('personal_division')) {
            
        
        Schema::create('personal_division', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->constrained();
            $table->foreignId('division_id')->constrained();
            $table->timestamps();
        });
         }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('personal_division')) {
        Schema::dropIfExists('personal_division');
        }
    }
};
