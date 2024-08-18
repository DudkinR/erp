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
        //table rooms delete colmbn address, add column square, floor 
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->string('square')->nullable();
            $table->string('floor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::table('rooms', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->dropColumn('square');
            $table->dropColumn('floor');
        });
    }
};
