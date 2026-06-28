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
        Schema::table('processes', function (Blueprint $table) {
            // Додаємо поле parent_id типу unsignedBigInteger (або integer, залежно від вашого id)
            // За замовчуванням встановлюємо 0, як ви просили
            $table->unsignedBigInteger('parent_id')
                  ->default(0)
                  ->after('id');

            // Створюємо індекс для прискорення рекурсивних вибірок (ієрархічних запитів)
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('processes', function (Blueprint $table) {
            // Видаляємо індекс та стовпчик у разі відкату міграції
            $table->dropIndex(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
