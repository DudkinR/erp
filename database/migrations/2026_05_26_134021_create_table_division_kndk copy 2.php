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
        // 1. Таблиця процесів
        if (!Schema::hasTable('processes')) {
            Schema::create('processes', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // ЗМІНЕНО: text на string
                $table->text('description')->nullable(); 
                $table->timestamps();
            });
        }

        // 2. Таблиця зв'язку (Pivot table)
        if (!Schema::hasTable('kndk_process')) {
            Schema::create('kndk_process', function (Blueprint $table) {
                $table->id();
                
                $table->foreignId('kndk_id')
                      ->constrained('kndk')
                      ->onDelete('cascade');

                $table->foreignId('process_id')
                      ->constrained('processes') // ТУТ БУЛА ПОМИЛКА: назва мала збігатися з таблицею вище
                      ->onDelete('cascade');

                $table->timestamps();
                
                $table->unique(['kndk_id', 'process_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kndk_process');
        Schema::dropIfExists('processes');
    }
};
