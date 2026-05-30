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
        // 1. Таблиця зв'язку з підрозділами
        if (!Schema::hasTable('division_kndk')) {

                Schema::create('division_kndk', function (Blueprint $table) {
                    $table->id();
                    
                    $table->foreignId('division_id')
                          ->constrained('divisions')
                          ->onDelete('cascade');
                          
                    $table->foreignId('kndk_id')
                          ->constrained('kndk')
                          ->onDelete('cascade');

                    $table->timestamps();
                    $table->unique(['division_id', 'kndk_id']);
                });
           
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_kndk');

    }
};
