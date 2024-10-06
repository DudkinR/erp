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
        if (Schema::hasTable('experiences')) {
            return;
        }
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->text('text_uk');
            // ru
            $table->text('text_ru');
            // en
            $table->text('text_en');
            $table->integer('npp');
            // year
            $table->integer('year');
            $table->integer('consequence');
            //accepted 
            $table->integer('accepted')->default(0);
            // author_tn
            $table->integer('author_tn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('experiences')) {
            return;
        }
        Schema::dropIfExists('experiences');
    }
};
