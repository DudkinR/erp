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
        if (Schema::hasTable('events_projects')) {
            return;
        }
        Schema::create('events_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            // division responsible for the event
            $table->foreignId('division_id')->constrained('divisions')->onDelete('cascade');
            // position responsible for the event
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('events_projects')) {
            return;
        }   
        Schema::dropIfExists('events_projects');
    }
};
