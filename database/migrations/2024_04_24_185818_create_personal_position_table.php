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
        if (Schema::hasTable('personal_position')) {
            return;
        }
        Schema::create('personal_position', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id');
            $table->foreignId('position_id');
        });
        
        Schema::table('personal_position', function (Blueprint $table) {
            $table->foreign('personal_id')->references('id')->on('personal')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('personal_position')) {
            return;
        }
        Schema::dropIfExists('personal_position');
    }
};
