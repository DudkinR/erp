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
        Schema::create('objective_objective', function (Blueprint $table) {
            $table->id();
            // parent objective
            $table->foreignId('parent_objective_id')->nullable()
            ->default(null)
            ->constrained('objectives')->onDelete('cascade');
            // child objective
            $table->foreignId('child_objective_id')->nullable()->constrained('objectives')->onDelete('cascade');           
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objective_objective');
    }
};
