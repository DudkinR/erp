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
        if (Schema::hasTable('providers')) {
            return;
        }
        Schema::create('providers', function (Blueprint $table) {
            $table->id();

            $table->string('full_name');              // Повна назва
            $table->string('short_name')->nullable(); // Коротка назва
            $table->string('ownership_form')->nullable(); // Форма власності
            $table->string('edrpou_code', 10)->nullable(); // Код за ЄДРПОУ
            $table->string('country')->nullable();    // Країна
            $table->text('products_services')->nullable(); // Продукція та послуги
            $table->string('decision_number')->nullable(); // № рішення
            $table->date('decision_date')->nullable();     // Дата останнього рішення
            $table->date('valid_until')->nullable();       // Дійсний до
            $table->text('notes')->nullable();             // Примітки

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
