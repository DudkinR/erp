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
        if (Schema::hasTable('magtable_magcolumn')) {
            return;
        }
        Schema::create('magtable_magcolumn', function (Blueprint $table) {
            $table->id();
            // connecting to magtables and magcolumns
            $table->foreignId('magtable_id')->constrained();
            $table->foreignId('magcolumn_id')->constrained();
            // count of columns
            $table->integer('number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magtable_magcolumn')) {
            return;
        }
        Schema::dropIfExists('magtable_magcolumn');
    }
};
