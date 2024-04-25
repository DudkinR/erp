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
        if (Schema::hasTable('status_personal')) {
            return;
        }
        Schema::create('status_personal', function (Blueprint $table) {
            $table->id();
            // belong to personal
            $table->foreignId('personal_id')->constrained('personal');
            // belong to status
            $table->foreignId('status_id')->constrained('status');
            $table->date('date_start');
            $table->date('date_end');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('status_personal')) {
            return;
        }
        Schema::dropIfExists('status_personal');
    }
};
