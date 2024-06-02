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
        if (!Schema::hasTable('objective_goal')) {
            Schema::create('objective_goal', function (Blueprint $table) {
                $table->id();
                $table->foreignId('objective_id')->constrained();
                $table->foreignId('goal_id')->constrained();
            });
        }
        if (!Schema::hasTable('objective_funct')) {
            Schema::create('objective_funct', function (Blueprint $table) {
                $table->id();
                $table->foreignId('objective_id')->constrained('objectives');
                $table->integer('funct_id')->constrained('functs');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('objective_funct')) {
            Schema::dropIfExists('objective_funct');
        }
        if (Schema::hasTable('objective_goal')) {
            Schema::dropIfExists('objective_goal');
        }
    }
};
