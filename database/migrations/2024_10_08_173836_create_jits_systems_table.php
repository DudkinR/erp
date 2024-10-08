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
        Schema::create('jits_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jit_id')->constrained('jits')->onDelete('cascade');
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jits_systems');
    }
};
