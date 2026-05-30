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
  
        if (!Schema::hasTable('kndk_responsible')) {
            Schema::create('kndk_responsible', function (Blueprint $table) {
                $table->id();
                
                $table->foreignId('kndk_id')
                      ->constrained('kndk')
                      ->onDelete('cascade');

                $table->foreignId('position_id')
                      ->constrained('positions') // ТУТ БУЛА ПОМИЛКА: назва мала збігатися з таблицею вище
                      ->onDelete('cascade');

                $table->timestamps();
                
                $table->unique(['kndk_id', 'position_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kndk_responsible');
    }
};
