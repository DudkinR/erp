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
        if (Schema::hasTable('divitions_systems')) {
            return;
        }
        Schema::create('divitions_systems', function (Blueprint $table) {
            $table->id();
             $table->foreignId('divition_id')->constrained('divitions')->onDelete('cascade');
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('divitions_systems')) {
            return;
        }
        Schema::dropIfExists('divitions_systems');
    }
};
