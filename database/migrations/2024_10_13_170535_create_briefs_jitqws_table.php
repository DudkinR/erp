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
        if (Schema::hasTable('briefs_jitqws')) {
            return;
        }
        Schema::create('briefs_jitqws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brief_id')->constrained('briefs');
            $table->foreignId('jitqw_id')->constrained('jitqws');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('briefs_jitqws')) {
            return;
        }
        Schema::dropIfExists('briefs_jitqws');
    }
};
