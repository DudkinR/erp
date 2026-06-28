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
         if (!Schema::hasColumn('kndk_position', 'division_id')) {
        Schema::table('kndk_position', function (Blueprint $table) {
            // Додаємо зовнішній ключ на таблицю divisions
            $table->foreignId('division_id')
                ->nullable() // або ->constrained() без nullable, якщо підрозділ обов'язковий
                ->constrained('divisions') 
                ->nullOnDelete(); // якщо підрозділ видалять, зв'язок залишиться, але поле стане null
        });
         }
       
         
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         if (Schema::hasColumn('kndk_position', 'division_id')) {
        Schema::table('kndk_position', function (Blueprint $table) {
            // 1. Спочатку видаляємо зв'язок (foreign key)
            $table->dropForeign(['division_id']);
            
            // 2. Потім видаляємо саму колонку
            $table->dropColumn('division_id');
        });
         }
    }
};
