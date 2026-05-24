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
         if (Schema::hasTable('document_kndk')) {
            return;
        }
        // Имя таблицы по конвенции Laravel формируется в алфавитном порядке: document_kndk
        Schema::create('document_kndk', function (Blueprint $table) {
            $table->id();

            // Связь с таблицей документов (тип string, так как inv_no — строка)
            $table->string('document_inv_no');
            
            // Связь с таблицей Kndk (предполагаем, что там стандартный unsignedBigInteger)
            $table->unsignedBigInteger('kndk_id'); 

            // Внешние ключи для целостности данных
            $table->foreign('document_inv_no')
                  ->references('inv_no')
                  ->on('documents')
                  ->onDelete('cascade');

            $table->foreign('kndk_id')
                  ->references('id')
                  ->on('kndks') // Убедитесь, что имя таблицы Kndk совпадает
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_kndk');
    }
};
