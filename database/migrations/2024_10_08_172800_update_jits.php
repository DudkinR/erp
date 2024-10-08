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
        if (!Schema::hasColumn('jits', 'name_ru')) {
            Schema::table('jits', function (Blueprint $table) {
                // rename column name to name_uk
                $table->renameColumn('name', 'name_uk');
                $table->renameColumn('description', 'description_uk');
                $table->string('name_ru')->nullable();
                $table->text('description_ru')->nullable();
                //english
                $table->string('name_en')->nullable();
                $table->text('description_en')->nullable();
                $table->text('keywords')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('jits', 'name_ru')) {
            Schema::table('jits', function (Blueprint $table) {
                // rename column name to name_uk
                $table->renameColumn('name_uk', 'name');
                $table->renameColumn('description_uk', 'description');
                $table->dropColumn('name_ru');
                $table->dropColumn('description_ru');
                $table->dropColumn('name_en');
                $table->dropColumn('description_en');
                $table->dropColumn('keywords');
            });
        }
    }
};
