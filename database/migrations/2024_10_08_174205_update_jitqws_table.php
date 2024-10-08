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
        // if has column description_ru
        if (!Schema::hasColumn('jitqws', 'description_ru')) {
            Schema::table('jitqws', function (Blueprint $table) {
                // update description to description_uk
                $table->renameColumn('description', 'description_uk');
                $table->text('description_ru')->nullable();
                $table->text('description_en')->nullable();
            });
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('jitqws', 'description_ru')) {
            Schema::table('jitqws', function (Blueprint $table) {
                // rename column name to name_uk
                $table->renameColumn('description_uk', 'description');
                $table->dropColumn('description_ru');
                $table->dropColumn('description_en');
            });
        }
    }
};
