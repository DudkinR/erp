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
        //creterias
        Schema::table('criterias', function (Blueprint $table) {
            // add column weight
            $table->integer('weight')->nullable()->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('criterias', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};
