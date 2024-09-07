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
        if (Schema::hasTable('magdataint')) {
            return;
        }
        Schema::create('magdataint', function (Blueprint $table) {
            $table->id();
            $table->integer('data');
            $table->integer('worker_tn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magdataint')) {
            return;
        }
        Schema::dropIfExists('magdataint');
    }
};
