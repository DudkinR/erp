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
        if (Schema::hasTable('form_item')) {
            return;
        }
        Schema::create('form_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms');
            $table->foreignId('item_id')->constrained('items');
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->Integer('author_tn');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('form_item')) {
            return;
        }
        Schema::dropIfExists('form_item');
    }
};
