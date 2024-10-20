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
        Schema::table('events_projects', function (Blueprint $table) {
            // update col nullable()
            $table->unsignedBigInteger('position_id')->nullable()->change();
            $table->unsignedBigInteger('division_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('events_projects', function (Blueprint $table) {
            // update col nullable()
            $table->unsignedBigInteger('position_id')->nullable(false)->change();
            $table->unsignedBigInteger('division_id')->nullable(false)->change();
        });
    }
};
