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

     if (Schema::hasTable('documents')) {
            return;
        }
         Schema::create('documents', function (Blueprint $table) {
            // Инвентарный номер как уникальный первичный ключ
            $table->string('inv_no')->primary(); 
            
            $table->string('doc_type')->nullable(); // Вид документа
            $table->string('code')->nullable(); // Шифр
            $table->string('organization')->nullable(); // Організація
            $table->text('short_content')->nullable(); // Короткий зміст
            $table->date('date_reg')->nullable(); // Дата затв.
            $table->date('date_start')->nullable(); // Дата введення в дію
            $table->date('date_end')->nullable(); // Дата закінчення дії
            $table->text('distribution')->nullable(); // Розсилка
            $table->string('replaced_content')->nullable(); // Введений замість
            $table->string('replaced_by')->nullable(); // Замінений на
            $table->string('change_no')->nullable(); // № зміни
            $table->integer('page_count')->nullable(); // К-сть сторінок
            $table->text('note')->nullable(); // Примітка
            $table->string('storage_location')->nullable(); // Місце реєстрації оригіналу
            $table->date('registration_date')->nullable(); // Дата реєстрації
            $table->boolean('is_cancelled')->default(false); // Анульований
            $table->date('cancellation_date')->nullable(); // Дата анулювання
            $table->boolean('is_reissued')->default(false); // Перевиданий
            $table->string('author')->nullable(); // Автор
            $table->string('approved_by')->nullable(); // Затвердив
            $table->string('project')->nullable(); // Проект
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
