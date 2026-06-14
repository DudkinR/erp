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
        Schema::create('kndk_position', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kndk_id')
                ->constrained('kndks')
                ->onDelete('cascade');

            $table->foreignId('position_id')
                ->constrained('positions')
                ->onDelete('cascade');

            $table->timestamps();

            // захист від дублювання зв’язків
            $table->unique(['kndk_id', 'position_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kndk_position');
    }
};
