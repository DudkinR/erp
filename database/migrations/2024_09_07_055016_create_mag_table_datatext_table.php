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
        if (Schema::hasTable('mag_column_datatext')) {
            return;
        }
        Schema::create('mag_column_datatext', function (Blueprint $table) {
            $table->id();
            // connecting to magtables and magdatatext
            $table->foreignId('magcolumn_id')->constrained();
            $table->foreignId('magdatatext_id')->constrained();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('mag_column_datatext')) {
            return;
        }
        Schema::dropIfExists('mag_column_datatext');
    }
};
