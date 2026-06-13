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
        if (!Schema::hasTable('division_process')) {
      
            Schema::create('division_process', function (Blueprint $table) {
                    $table->id();
                    
                    // Зовнішні ключі з каскадним видаленням
                    $table->foreignId('process_id')->constrained('processes')->onDelete('cascade');
                    $table->foreignId('division_id')->constrained('divisions')->onDelete('cascade');
                    
                    $table->timestamps();

                    // Гарантує унікальність пари на рівні бази даних
                    $table->unique(['process_id', 'division_id']);
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_process');
    }
};
