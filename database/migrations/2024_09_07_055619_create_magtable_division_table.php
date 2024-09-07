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
    {if (Schema::hasTable('magtable_division')) {
            return;
        }
        Schema::create('magtable_division', function (Blueprint $table) {
            $table->id();
            // connecting to magtables and divisions
            $table->foreignId('magtable_id')->constrained();
            $table->foreignId('division_id')->constrained();
            // type of division
            $table->integer('type')->default(0);
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magtable_division')) {
            return;
        }
        Schema::dropIfExists('magtable_division');
    }
};
