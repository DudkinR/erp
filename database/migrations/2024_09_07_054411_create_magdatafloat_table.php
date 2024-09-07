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
        if (Schema::hasTable('magdatafloat')) {
            return;
        }
        Schema::create('magdatafloat', function (Blueprint $table) {
            $table->id();
            $table->float('data');
            $table->integer('worker_tn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magdatafloat')) {
            return;
        }
        Schema::dropIfExists('magdatafloat');
    }
};
