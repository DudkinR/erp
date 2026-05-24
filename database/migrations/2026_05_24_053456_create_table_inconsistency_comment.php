<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inconsistency_comments', function (Blueprint $table) {
            $table->id();

            // зовнішні ключі
            $table->unsignedBigInteger('comment_id');
            $table->unsignedBigInteger('inconsistency_id');

            $table->foreign('comment_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');

            $table->foreign('inconsistency_id')
                  ->references('id')
                  ->on('inconsistencs') // саме так, як у тебе створена таблиця
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inconsistency_comments');
    }
};
