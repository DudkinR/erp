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
        if (Schema::hasTable('magcolumns')) {
            return;
        }
        Schema::create('magcolumns', function (Blueprint $table) {
            $table->id();
            // name of the column
            $table->string('name');
            // description of the column
            $table->text('description')->nullable();
            // the type of the column (0 string, 1 integer, 2 text, etc.)
            $table->integer('type');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('magcolumns')) {
            return;
        }
        Schema::dropIfExists('magcolumns');
    }
};
