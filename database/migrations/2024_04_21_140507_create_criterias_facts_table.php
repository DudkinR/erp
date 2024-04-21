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
        if (Schema::hasTable('criterias_facts')) {
            return;
        }
        Schema::create('criterias_facts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained()->onDelete('cascade');
            $table->foreignId('fact_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('criterias_facts')) {
            return;
        }
        Schema::dropIfExists('criterias_facts');
    }
};
