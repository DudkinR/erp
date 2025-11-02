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

        if (!Schema::hasTable('proposals')) {
            Schema::create('proposals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('division_id')->constrained('divisions')->onDelete('cascade');
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->text('proposal')->nullable();
                $table->enum('status', ['на розгляді', 'схвалено', 'відхилено', 'у доопрацюванні'])->default('на розгляді');
                $table->text('decision')->nullable();   
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('proposals')) {
            Schema::dropIfExists('proposals');
        }
    }
};
