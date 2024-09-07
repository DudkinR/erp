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
        if (Schema::hasTable('magcolumns_maglimits')) {
            return;
        }
        Schema::create('magcolumns_maglimits', function (Blueprint $table) {
            $table->id();
            // connecting to magcolumns and maglimits
            $table->foreignId('magcolumn_id')->constrained();
            $table->foreignId('maglimit_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magcolumns_maglimits')) {
            return;
        }
        Schema::dropIfExists('magcolumns_maglimits');
    }
};
