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
        if (Schema::hasTable('clients')) {
            return;
        }
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            // Найменування	Бізнес-регіон	Дата реєстрації	Код
            $table->string('name');
            $table->string('business_region');
            $table->date('registration_date');
            $table->string('code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('clients')) {
            return;
        }
        Schema::dropIfExists('clients');
    }
};
