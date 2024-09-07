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
        if (Schema::hasTable('mag_column_datatime')) {
            return;
        }
        Schema::create('mag_column_datatime', function (Blueprint $table) {
            $table->id();
            // connecting to magtables and magdatatime
            $table->foreignId('magcolumn_id')->constrained();
            $table->foreignId('magdatatime_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('mag_column_datatime')) {
            return;
        }
        Schema::dropIfExists('mag_column_datatime');
    }
};
