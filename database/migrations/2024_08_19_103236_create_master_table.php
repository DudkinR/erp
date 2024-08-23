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
        if (Schema::hasTable('master')) {
            return;
        }
        Schema::create('master', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('personal')->nullable();
            // text 
            $table->text('text');
            // basis
            $table->text('basis')->nullable();
            // хто записав або дав доручення 
            $table->text('who');
            // терміновість 
            $table->tinyInteger('urgency')->default(0);
            // дата час крайнього терміну
            $table->timestamp('deadline')->nullable();
            // оцінка виконання в годинах
            $table->tinyInteger('estimate')->default(1);
            // початок виконання
            $table->timestamp('start')->nullable();
            // закінчення виконання
            $table->timestamp('end')->nullable();
            // виконано
            $table->boolean('done')->default(false);
            // коментар
            $table->text('comment') ->nullable();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master');
      

    }
};
