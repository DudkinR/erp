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
        if (Schema::hasTable('projects')) {
            return;
        }
        Schema::create('projects', function (Blueprint $table) {
            //Пріоритет	Номер	Дата	Сума	Клієнт	Поточний стан	Строк виконання	% оплати	% відвантаження	% боргу	Валюта	Операція
            $table->id();
            $table->string('name'); 
            $table->string('priority')->nullable();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->string('amount')->nullable();
            $table->string('client')->nullable();
            $table->string('current_state')->nullable();
            $table->string('execution_period')->nullable();
            $table->string('payment_percentage')->nullable();
            $table->string('shipping_percentage')->nullable();
            $table->string('debt_percentage')->nullable();
            $table->string('currency')->nullable();
            $table->string('operation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('projects')) {
            return;
        }
        Schema::dropIfExists('projects');
    }
};
