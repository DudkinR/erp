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
        if (Schema::hasTable('places')) {
            return;
        }
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            // name
            $table->string('name');
            // description
            $table->text('description')->nullable();
            // долгота широта висота
            $table->string('latitude');
            $table->string('longitude');
            $table->string('altitude');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('places')) {
            return;
        }
        Schema::dropIfExists('places');
    }
};
