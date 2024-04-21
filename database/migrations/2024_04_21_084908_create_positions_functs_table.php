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
        if(!Schema::hasTable('positions')) {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('positions_functs')) {

        Schema::create('positions_functs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained();
            $table->foreignId('funct_id')->constrained();
            $table->integer('order');

        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('positions_functs')) {
        Schema::dropIfExists('positions_functs');
        }
    }
};
