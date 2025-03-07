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
        if (Schema::hasTable('nomenclature')) {
            return;
        }
        Schema::create('nomenclature', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('article')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('nomenclature')) {
            return;
        }
        Schema::dropIfExists('nomenclature');
    }
};
