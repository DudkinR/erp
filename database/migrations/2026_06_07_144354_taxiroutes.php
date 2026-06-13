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
      // 1. Якщо таблиці взагалі немає — створюємо її з базовими колонками
        if (!Schema::hasTable('taxiroutes')) {
            Schema::create('taxiroutes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('car_id');
                $table->date('date');
                $table->time('time');
                $table->timestamps();
            });
        }

        // 2. Перевіряємо, чи немає колонки 'division_id' перед її додаванням
        if (!Schema::hasColumn('taxiroutes', 'division_id')) {
            Schema::table('taxiroutes', function (Blueprint $table) {
                // Тут 'after' працює коректно, бо це Schema::table
                $table->unsignedBigInteger('division_id')->nullable()->after('car_id');
                
                $table->foreign('division_id')
                      ->references('id')
                      ->on('divisions')
                      ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taxiroutes', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });
    }
};
