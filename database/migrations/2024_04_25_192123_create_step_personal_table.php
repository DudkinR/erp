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
        if (Schema::hasTable('step_personal')) {
            return;
        }
        Schema::create('step_personal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained();
            $table->foreignId('personal_id')->constrained('personal');
            $table->string('status')->nullable()->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('step_personal')) {
            return;
        }
        Schema::dropIfExists('step_personal');
    }
};
