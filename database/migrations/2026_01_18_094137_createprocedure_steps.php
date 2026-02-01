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
        Schema::create('procedure_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procedure_id')->constrained('procedures')->onDelete('cascade');
            $table->string('problem');       // опис проблеми
            $table->text('solution');        // як виправити
            $table->text('copy_text');       // текст для копіювання
            $table->boolean('is_loop')->default(false); // внутрішній цикл
            $table->boolean('is_end')->default(false);  // кінець процедури
            $table->integer('order')->default(0);       // порядок виконання
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
