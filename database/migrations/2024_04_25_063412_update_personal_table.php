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
        //
        Schema::table('personal', function (Blueprint $table) {
            //'nickname',  'email', 'phone' =  nullable
            $table->string('nickname')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            // add column Дата прийому на роботу

            $table->date('date_start')->nullable()->after('phone');
            // Стан
            $table->string('status')->nullable()->after('date_start');
            //Стан Діє до, default nullable
            $table->date('date_status')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('personal', function (Blueprint $table) {
            $table->string('nickname')->change();
            $table->string('email')->change();
            $table->string('phone')->change();
            $table->dropColumn('date_start');
            $table->dropColumn('status');
            $table->dropColumn('date_status');
        });
    }
};
