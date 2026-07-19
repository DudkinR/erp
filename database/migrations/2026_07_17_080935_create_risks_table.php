<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('risks')) {
            // 1. Таблиця самих ризиків
            Schema::create('risks', function (Blueprint $table) {
                $table->id();
                $table->string('name');              // Назва ризику (наприклад: "Недотримання регламенту ТО")
                $table->text('description')->nullable(); // Опис ризику, можливі наслідки
                $table->timestamps();
            });
        }
         if (!Schema::hasTable('kndk_risk')) {
            // 2. Pivot-таблиця зв'язку між КНДК та Ризиками
            Schema::create('kndk_risk', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kndk_id')->constrained('kndk')->onDelete('cascade'); // Перевірте назву вашої таблиці kndk
                $table->foreignId('risk_id')->constrained('risks')->onDelete('cascade');
                $table->timestamps();
            });
         }
    }

    public function down(): void
    {
        if (Schema::hasTable('kndk_risk')) {
        Schema::dropIfExists('kndk_risk');
        }
        if (Schema::hasTable('risks')) {
        Schema::dropIfExists('risks');
        }
    }
};
