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
        //add column to positions_functs table after position_id column division_id
        if (!Schema::hasColumn('positions_functs', 'division_id')) {
            Schema::table('positions_functs', function (Blueprint $table) {
                $table->unsignedBigInteger('division_id')->nullable()->after('position_id');
                $table->foreign('division_id')->references('id')->on('divisions');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('positions_functs', 'division_id')) {
            Schema::table('positions_functs', function (Blueprint $table) {
                $table->dropForeign(['division_id']);
                $table->dropColumn('division_id');
            });
        }
    }
};
