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
        if (Schema::hasTable('mistakes')) {
            return;
        }
        Schema::create('mistakes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();            
            $table->text('text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('mistakes')) {
            return;
        }
        Schema::dropIfExists('mistakes');
    }
};
