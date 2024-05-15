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
        // if not col lng add after slug  default uk
        if (!Schema::hasColumn('documentations', 'lng')) {
        Schema::table('documentations', function (Blueprint $table) {

            $table->string('lng')->after('slug')->default('uk');
        });
      }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            //
            if (Schema::hasColumn('documentations', 'lng')) {
                Schema::table('documentations', function (Blueprint $table) {
                    $table->dropColumn('lng');
                });
            }
            
    }
};
