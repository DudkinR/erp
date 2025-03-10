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
        if (Schema::hasTable('wanoarea')) {
            return;
        }
        Schema::create('wanoarea', function (Blueprint $table) {
            $table->id();
            $table->string('abv')->nullable();
            $table->string('name');
            // focus
            $table->string('focus')->nullable();
            // description text
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(!Schema::hasTable('wanoarea')) {
            return;
        }
        Schema::dropIfExists('wanoarea');
    }
};
