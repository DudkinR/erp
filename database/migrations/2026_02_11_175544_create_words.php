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
        // Create 'words' table if not exists
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->string('bedword');
            $table->string('comment')->nullable();
            $table->unsignedTinyInteger('type')->default(1); // 1,2,3 with default 1
            $table->timestamps();
        });

        // Create pivot table 'word_user' if not exists
        Schema::create('word_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('word_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {//if
         Schema::dropIfExists('word_user');
        Schema::dropIfExists('words');

    }
};
