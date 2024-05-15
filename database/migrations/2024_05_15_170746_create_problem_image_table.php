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
        if (Schema::hasTable('problem_image')) {
            return;
        }
        Schema::create('problem_image', function (Blueprint $table) {
            $table->id();
            $table->foreignId('problem_id')->constrained('problems');
            $table->foreignId('image_id')->constrained('images');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('problem_image')) {
            return;
        }
        Schema::dropIfExists('problem_image');
    }
};
