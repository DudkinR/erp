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
        if (Schema::hasTable('magtable_magmem')) {
            return;
        }
        Schema::create('magtable_magmem', function (Blueprint $table) {
            $table->id();
            // connecting to magtables and magmems
            $table->foreignId('magtable_id')->constrained();
            $table->foreignId('magmem_id')->constrained();
            // number 
            $table->integer('number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magtable_magmem')) {
            return;
        }
        Schema::dropIfExists('magtable_magmem');
    }
};
