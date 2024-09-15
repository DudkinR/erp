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
        if (Schema::hasTable('forms')) {
            return;
        }
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // description
            $table->text('description')->nullable();
            // status
            $table->tinyInteger('status')->default(0);
            // aythor
            $table->Integer('author_tn'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('forms')) {
            return;
        }
        Schema::dropIfExists('forms');
    }
};
