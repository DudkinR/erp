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
        if (Schema::hasTable('briefing_personal')) {
            return;
        }
        Schema::create('briefing_personal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('briefing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('personal_id')->constrained()->cascadeOnDelete();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('briefing_personal');
    }
};
