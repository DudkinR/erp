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
        // add Введена,	Дата введення,	Виключена,	Дата виключення

        Schema::table('positions', function (Blueprint $table) {
            $table->string('start')->nullable()->default('Ні')->after('description');
            $table->date('data_start')->nullable()->after('start');
            $table->string('closed')->nullable()->default('Ні')->after('data_start');
            $table->date('data_closed')->nullable()->after('closed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn('start');
            $table->dropColumn('data_start');
            $table->dropColumn('closed');
            $table->dropColumn('data_closed');
        });
    }
};
