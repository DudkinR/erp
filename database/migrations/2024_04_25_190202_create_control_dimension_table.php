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
        if (Schema::hasTable('control_dimension')) {
            return;
        }
        Schema::create('control_dimension', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_id')->constrained();
            $table->foreignId('dimension_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('control_dimension')) {
            return;
        }
        Schema::dropIfExists('control_dimension');
    }
};
