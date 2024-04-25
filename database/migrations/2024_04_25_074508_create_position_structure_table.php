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
        Schema::dropIfExists('positions_structuries');
    
        Schema::create('positions_structuries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('positions_id')->constrained()->onDelete('cascade');
            $table->foreignId('structuries_id')->constrained()->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('positions_structuries')) {
            return;
        }
        Schema::dropIfExists('positions_structuries');
    }
};
