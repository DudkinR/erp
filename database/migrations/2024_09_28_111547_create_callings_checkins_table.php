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
        if (Schema::hasTable('callings_checkins')) {
            return;
        }
        Schema::create('callings_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calling_id')->constrained();
            // personal__id
            $table->foreignId('personal_id')->constrained();
            // checkin TYPE
            $table->foreignId('checkin_type_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('callings_checkins')) {
            return;
        }
        Schema::dropIfExists('callings_checkins');
    }
};
