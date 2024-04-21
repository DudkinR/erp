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
        if (Schema::hasTable('facts_acts')) {
            return;
        }
        Schema::create('facts_acts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fact_id')->constrained()->onDelete('cascade');
            $table->foreignId('act_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('facts_acts')) {
            return;
        }
        Schema::dropIfExists('facts_acts');
    }
};
