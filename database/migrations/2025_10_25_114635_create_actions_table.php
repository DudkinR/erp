<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (!Schema::hasTable('actions')) {
            Schema::create('actions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proposal_id')->constrained('proposals')->onDelete('cascade');
                $table->string('title')->nullable();
                $table->string('responsible')->nullable();
                $table->date('deadline')->nullable();
                $table->enum('status', ['в процесі', 'виконано', 'не виконано'])->default('в процесі');
                $table->text('result_description')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('actions')) {
            Schema::dropIfExists('actions');
        }
    }
};
