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
         Schema::table('kndk_position', function (Blueprint $table) {
            // Додаємо поле ролі (власник/виконавець). 
            // За замовчуванням робимо 'executor' (виконавець), щоб не зламати старі дані.
            $table->string('role', 30)->default('executor')->after('position_id');
            
            // Створюємо індекс для швидкої фільтрації за роллю
            $table->index(['kndk_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('kndk_position', function (Blueprint $table) {
            $table->dropIndex(['kndk_id', 'role']);
            $table->dropColumn('role');
        });
    }
};
