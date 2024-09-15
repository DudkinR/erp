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
        if (Schema::hasTable('items')) {
            return;
        }
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable();
            // status
            $table->tinyInteger('status')->default(0);  
            // autor
            $table->Integer('author_tn'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('items')) {
            return;
        }
        Schema::dropIfExists('items');
    }
};
