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
         if (Schema::hasTable('kndks')) {
            return;
        }
         Schema::create('kndks', function (Blueprint $table) {
            $table->id();
            
            // Ієрархічні рівні класифікації
            $table->tinyInteger('class')->unsigned()->comment('Перший рівень (1-6)');
            $table->string('subclass', 2)->nullable()->comment('Другий рівень (двозначний код)');
            $table->string('group', 2)->nullable()->comment('Третій рівень (двозначний код)');
            
            // Повний текстовий код для зручності відображення (напр., "1.40.05")
            $table->string('full_code', 10)->unique()->comment('Повний код формування X.XX.XX.XX');
            
            // Текстові назви та об'єкти
            $table->string('name')->comment('Назва сфери, напряму або виду діяльності');
            $table->enum('object_type', ['document', 'function', 'event'])->nullable()->comment('Обʼєкт: документ, функція, захід');
            
            $table->timestamps();

            // Індекси для супер-швидкого пошуку та фільтрації
            $table->index(['class', 'subclass', 'group']);
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kndks');
    }
};
