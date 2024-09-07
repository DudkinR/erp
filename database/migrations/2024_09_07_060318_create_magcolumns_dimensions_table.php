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
        if (Schema::hasTable('magcolumns_dimensions')) {
            return;
        }
        Schema::create('magcolumns_dimensions', function (Blueprint $table) {
            $table->id();
            // connecting to magcolumns and dimensions
            $table->foreignId('magcolumn_id')->constrained();
            $table->foreignId('dimension_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magcolumns_dimensions')) {
            return;
        }
        Schema::dropIfExists('magcolumns_dimensions');
    }
};
